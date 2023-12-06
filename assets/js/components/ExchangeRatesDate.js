import React, { useState, useEffect } from 'react';
import axios from 'axios';
import { useLocation } from 'react-router-dom';
import DatePicker from './DatePicker';

const ExchangeRatesDate = () => {
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
        setLoading(true);

        const baseUrl = 'http://127.0.0.1:8000';
        const actionApi = `/api/exchange-rates/${selectedDate}`;

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
      <section className="row-section">
        <div className="container">
          <div className="row mt-5">
            <div className="col-md-8 offset-md-2 d-flex justify-content-center">
              <h1 >Kursy Walut na Konkretną Datę</h1>
            </div>
            <div className="col-md-8 offset-md-2 d-flex justify-content-center">
              <DatePicker selectedDate={selectedDate} setSelectedDate={setSelectedDate} />
            </div>
            {loading ? (
              <div className="col-md-8 offset-md-2 d-flex justify-content-center">
                <p>Pobieranie danych...</p>
              </div>
            ) : (
              <>
                {error ? (
                  <div className="col-md-8 offset-md-2 d-flex justify-content-center">
                    <p className="text-danger">{error}</p>
                  </div>
                ) : (
                  <>
                    {exchangeRates.length > 0 && (
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