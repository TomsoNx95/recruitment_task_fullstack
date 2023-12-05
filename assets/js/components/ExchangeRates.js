import React, { useState, useEffect } from 'react';
import axios from 'axios';
import DatePicker from './DatePicker';
import { useLocation } from 'react-router-dom';

const ExchangeRates = () => {
  const location = useLocation();
  const queryParams = new URLSearchParams(location.search);
  const date = queryParams.get('date');

  const [exchangeRates, setExchangeRates] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);
  const [selectedDate, setSelectedDate] = useState(date);

  useEffect(() => {
    const fetchData = async () => {
      try {
        setLoading(true); // Ustaw stan wczytywania na true przed rozpoczęciem pobierania danych

        const baseUrl = 'http://127.0.0.1:8000';
        const actionApi = `/api/exchange-rates/${selectedDate}`;

        const response = await axios.get(baseUrl + actionApi);
        const formattedData = response.data.rates || [];
        setExchangeRates(formattedData);
        setError(null);
      } catch (error) {
        setError('Brak wyników proszę podaj inną datę');
      } finally {
        setLoading(false); // Niezależnie od tego, czy pobieranie danych zakończyło się sukcesem czy nie, ustaw stan wczytywania na false
      }
    };
    fetchData();
  }, [selectedDate]);

  return (
    <div>
      <section className="row-section">
        <div className="container">
          <div className="row mt-5">
            <div className="col-md-8 offset-md-2 d-flex justify-content-center">
              <h1 className="mb-5">Exchange Rates Test</h1>
            </div>
            <div className="col-md-8 offset-md-2 d-flex justify-content-center">
              <DatePicker selectedDate={selectedDate} setSelectedDate={setSelectedDate} />
            </div>
            {loading ? (
              <div className="col-md-8 offset-md-2 d-flex justify-content-center">
                <p>Pobieranie danych ...</p>
              </div>
            ) : (
              <>
                {error ? (
                  <div className="col-md-8 offset-md-2 d-flex justify-content-center">
                    <p>{error}</p>
                  </div>
                ) : (
                  <>
                    {exchangeRates.length > 0 && (
                      <table className="table">
                        <thead>
                          <tr>
                            <th>Waluta</th>
                            <th>Kurs Kupna</th>
                            <th>Kurs Sprzedaży</th>
                            <th>Średnia</th>
                            <th>Kurs Kupna (Dzisiaj)</th>
                            <th>Kurs Sprzedaży (Dzisiaj)</th>
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
                              <td>{rate.mid}</td>
                              <td>{rate.sellRate}</td>
                              <td style={{ color: rate.todayBuyRate ? 'black' : 'red' }}>
                                {rate.todayBuyRate ? rate.todayBuyRate : 'Brak kupna'}
                              </td>
                              <td>{rate.todaySellRate}</td>
                            </tr>
                          ))}
                        </tbody>
                      </table>
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

export default ExchangeRates;