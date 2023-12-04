import React, { useState, useEffect } from 'react';
import axios from 'axios';

const ExchangeRates = () => {
  const [exchangeRates, setExchangeRates] = useState([]);
  const [error, setError] = useState(null);

  useEffect(() => {
    const fetchData = async () => {
      try {
        const baseUrl = 'http://127.0.0.1:8000';
        const actionApi = '/api/exchange-rates';

        const response = await axios.get(baseUrl + actionApi);
        console.log(response.data);

        const formattedData = response.data.rates || [];

        setExchangeRates(formattedData);
      } catch (error) {
        setError('Unable to fetch exchange rates');
      }
    };

    fetchData();
  }, []);

  return (
    <div>
      <section className="row-section">
        <div className="container">
          <div className="row mt-5">
            <div className="col-md-8 offset-md-2 d-flex justify-content-center">
              <h1>Exchange Rates Test</h1>
            </div>
            {error && <p>{error}</p>}
            {exchangeRates.length > 0 && (
              <table className="table">
                <thead>
                  <tr>
                    <th>Currency</th>
                    <th>Code</th>
                    <th>Mid</th>
                  </tr>
                </thead>
                <tbody>
                  {exchangeRates.map((rate, index) => (
                    <tr key={index}>
                      <td>{rate.currency}</td>
                      <td>{rate.code}</td>
                      <td>{rate.mid}</td>
                    </tr>
                  ))}
                </tbody>
              </table>
            )}
          </div>
        </div>
      </section>
    </div>
  );
};

export default ExchangeRates;