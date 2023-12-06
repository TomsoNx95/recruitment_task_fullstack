import React from 'react';
import ExchangeRatesToday from './ExchangeRatesToday';
import ExchangeRatesDate from './ExchangeRatesDate';

const ExchangeRates = () => {
  return (
    <div style={{ display: 'flex' }}>
      <div style={{ flex: 1, marginRight: '20px' }}>
        <ExchangeRatesDate />
      </div>
      <div style={{ flex: 1 }}>
        <ExchangeRatesToday />
      </div>
    </div>
  );
};

export default ExchangeRates;