import React, { useState, useEffect } from 'react';
import axios from 'axios';

/**
 * Komponent ExchangeRatesToday odpowiedzialny za wyświetlanie dzisiejszych kursów walut.
 * Pobiera dane z API i renderuje tabelę z informacjami o aktualnych kursach.
 * Zastosowałem komponent funkcyjny, ponieważ są one zalecane od pewnej wersji reacta
 */
const ExchangeRatesToday = () => {
  // Stany przechowujące dane o kursach, informację o ładowaniu i ewentualny błąd.
  const [exchangeRates, setExchangeRates] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);

  // Efekt pobierający dzisiejsze kursy po zamontowaniu komponentu.
  useEffect(() => {
    const fetchData = async () => {
      try {
        setLoading(true);

        // Bazowy URL API i akcja dla dzisiejszych kursów.
        const baseUrl = 'http://127.0.0.1:8000';
        const actionApi = '/api/exchange-rates/today';

        // Pobranie danych z API.
        const response = await axios.get(baseUrl + actionApi);
        const formattedData = response.data.rates || [];
        setExchangeRates(formattedData);
        setError(null);
      } catch (error) {
        setError('Brak kursu dla aktualnego dnia. Pamiętaj że kursy na dzień dzisiejszy są aktualizowane około godziny 12');
      } finally {
        setLoading(false);
      }
    };
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
                {error ? (
                  // Komunikat błędu
                  <div className="col-md-8 offset-md-2 d-flex justify-content-center">
                    <p className="text-danger">{error}</p>
                  </div>
                ) : (
                  <>
                    {exchangeRates.length > 0 && (
                      // Tabela z dzisiejszymi kursami
                      <div className="col-md-8 offset-md-2">
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
              </>
            )}
          </div>
        </div>
      </section>
    </div>
  );
};

export default ExchangeRatesToday;
