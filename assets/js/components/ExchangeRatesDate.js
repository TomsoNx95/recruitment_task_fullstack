import React, { useState, useEffect } from 'react';
import axios from 'axios';
import { useLocation } from 'react-router-dom';
import DatePicker from './DatePicker';
/**
 * Komponent ExchangeRatesDate odpowiedzialny za wyświetlanie kursów walut z dnia wybranego przez użytkownika.
 * Pobiera dane z API i renderuje tabelę z informacjami o kursach.
 * Zastosowałem komponent funkcyjny, ponieważ są one zalecane od pewnej wersji reacta
 */
const ExchangeRatesDate = () => {
  // Pobranie daty z parametrów URL
  const location = useLocation();
  const queryParams = new URLSearchParams(location.search);
  const date = queryParams.get('date');

  // Ustawienie stanów dla danych, stanu ładowania i ewentualnego błędu
  const [exchangeRates, setExchangeRates] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);
  const [selectedDate, setSelectedDate] = useState(date);

  // Efekt pobierający dane przy zmianie daty
  useEffect(() => {
    const fetchData = async () => {
      try {
        setLoading(true);

        // Ustawienie podstawowego URL API
        const baseUrl = 'http://127.0.0.1:8000';
        const actionApi = `/api/exchange-rates/${selectedDate}`;

        // Pobranie danych z API
        const response = await axios.get(baseUrl + actionApi);
        const formattedData = response.data.rates || [];
        setExchangeRates(formattedData);
        setError(null);
      } catch (error) {
        setError('Brak wyników. Proszę podać inną datę');
      } finally {
        setLoading(false);
      }
    };
    fetchData();
  }, [selectedDate]);

  return (
    <div>
      {/* Sekcja z tabelą kursów walut na konkretną datę */}
      <section className="row-section">
        <div className="container">
          <div className="row mt-5">
            <div className="col-md-8 offset-md-2 d-flex justify-content-center">
              <h1>Kursy Walut na Konkretną Datę</h1>
            </div>
            {/* Komponent do wybierania daty */}
            <div className="col-md-8 offset-md-2 d-flex justify-content-center">
              <DatePicker selectedDate={selectedDate} setSelectedDate={setSelectedDate} />
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
                      // Tabela z kursami walut
                      <div className="col-md-8 offset-md-2">
                        <table className="table table-bordered table-striped">
                          <thead className="thead-dark">
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

export default ExchangeRatesDate;