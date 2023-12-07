import React, { useState, useEffect } from 'react';
import axios from 'axios';
/**
 * Komponent ExchangeRatesToday odpowiedzialny za wyświetlanei dzisiejszych kursów walut.
 * Pobiera dane z API NBP i renderuje tabelę z aktualnymi kursami.
 * Zastosowałem komponent funkcyjny, ponieważ są one zalecane od pewnej wersji reacta.
 */
const ExchangeRatesToday = () => {
  // Stan przechowujący dane o kursach, informację o ładowaniu i ewentualny błąd.
  const [exchangeRates, setExchangeRates] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);

  // Funkcja do pobierania danych z API.
  const fetchData = async () => {
    try {
      setLoading(true);
      // Błędny baseUrl dla celów testowych - popraw go w rzeczywistości.
      const baseUrl = 'http://127.0.0.1:8000';
      const actionApi = '/api/exchange-rates/today';
      const response = await axios.get(baseUrl + actionApi);
      const formattedData = response.data.rates || [];
      setExchangeRates(formattedData);
      setError(null);
    } catch (error) {
      setError('Brak kursu dla aktualnego dnia. Pamiętaj, że kursy na dzień dzisiejszy są aktualizowane około godziny 12. Jeśli po aktualizacji nic się nie dzieje, to znaczy, że NBP nie zaktualizowało dzisiejszych kursów');
    } finally {
      setLoading(false);
    }
  };

  // Efekt pobierający dzisiejsze kursy po zamontowaniu komponentu.
  useEffect(() => {
    fetchData();
  }, []);

  return (
    <div>
      {/* Sekcja z dzisiejszymi kursami */}
      <section className="row-section">
        <div className="container">
          <div className="row mt-5">
            <div className="col-md-8 offset-md-2 d-flex justify-content-center">
              <h1 className="mb-5">Dzisiejsze Kursy Walut</h1>
            </div>
            {loading ? (
              // Komunikat ładowania danych
              <div className="col-md-8 offset-md-2 d-flex justify-content-center">
                <p>Pobieranie danych...</p>
              </div>
            ) : (
              <>
                {error && (
                  <div className="col-md-8 offset-md-2 d-flex flex-column align-items-center">
                    <p className="text-danger mb-3" style={{ wordWrap: 'break-word', whiteSpace: 'pre-wrap', textAlign: 'center' }}>
                      {error}
                    </p>
                    <button onClick={fetchData} className="btn btn-primary">Zaktualizuj</button>
                  </div>
                )}
                {!error && exchangeRates.length > 0 && (
                  // Tabela z dzisiejszymi kursami
                  <div className="col-md-8 offset-md-2">
                    <div className="col-md-8 offset-md-2 d-flex justify-content-center">
                      <button onClick={fetchData} className="btn btn-primary mb-3">Zaktualizuj</button>
                    </div>
                    <table className="table table-bordered">
                      <thead>
                        <tr>
                          <th>Waluta</th>
                          <th>Kurs Kupna</th>
                          <th>Kurs Sprzedaży</th>
                        </tr>
                      </thead>
                      <tbody>
                        {exchangeRates.map((rate, index) => (
                          <tr key={index}>
                            <td>
                              {rate.currency} <small>({rate.code})</small>
                            </td>
                            <td style={{ color: rate.buyRate ? 'black' : 'red' }}>
                              {rate.buyRate ? rate.buyRate : 'Brak kupna'}
                            </td>
                            <td>{rate.sellRate}</td>
                          </tr>
                        ))}
                      </tbody>
                    </table>
                  </div>
                )}
              </>
            )}
          </div>
        </div>
      </section>
    </div>
  );
};

export default ExchangeRatesToday;
