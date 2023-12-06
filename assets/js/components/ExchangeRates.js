import React from 'react';
// Zaczytuje komponenty funkcyjne odpowiedzialne za renderowanie tabel z aktualnymi kursami oraz z kursami z dnia wybranego przez użytkownika
import ExchangeRatesToday from './ExchangeRatesToday';
import ExchangeRatesDate from './ExchangeRatesDate';

const ExchangeRates = () => {
  return (
    // Kontener dla sekcji z datą i sekcji z dzisiejszymi kursami, używając flexboxa do ustawienia obok siebie
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