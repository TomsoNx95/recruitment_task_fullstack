import React, { useEffect } from 'react';
import { useHistory } from 'react-router-dom';

/**
 * Komponent DatePicker umożliwiający wybór daty za pomocą pola wejściowego typu 'date'.
 * Przyjmuje datę wybraną przez użytkownika oraz funkcję ustawiającą tę datę.
 * Przy zmianie daty, aktualizuje stan i aktualizuje adres URL za pomocą reaktywnego routingu.
 * Resetowanie daty jest zabezpieczone, po resecie przypisuje dzisiejszą datę
 * Maksymalna data to data dzisiejsza
 * Nie możemy ustawić daty mniejszej od roku 2023
 *
 * @param {Object} props - Właściwości komponentu.
 * @param {string} props.selectedDate - Aktualnie wybrana data.
 * @param {Function} props.setSelectedDate - Funkcja do ustawiania wybranej daty.
 * @returns {JSX.Element} Komponent DatePicker.
 */
const DatePicker = ({ selectedDate, setSelectedDate }) => {
  const currentDate = new Date();
  const currentYear = currentDate.getFullYear();
  const currentMonth = (currentDate.getMonth() + 1).toString().padStart(2, '0');
  const currentDay = currentDate.getDate().toString().padStart(2, '0');

  // Sprawdzenie czy podany rok nie jest mniejszy od aktualnego
  let maxYear = currentYear;

  const isValidYear = new Date(selectedDate).getFullYear() >= currentYear;
  if (!isValidYear) {
    maxYear = new Date().getFullYear();
  }

  const minDate = `${currentYear}-01-01`;
  const maxDate = `${maxYear}-${currentMonth}-${currentDay}`;

  // Pobranie obiektu historii routingu z React Router.
  const history = useHistory();

  /**
   * Obsługuje zmianę daty w polu wejściowym i aktualizuje stan oraz adres URL.
   * Jeśli data zostanie wyczyszczona, ustaw maksymalną dostępną datę.
   *
   * @param {Object} event - Obiekt zdarzenia zmiany wartości pola wejściowego.
   */
  const handleChange = (event) => {
    let newDate = event.target.value;

    const isValidYear = new Date(newDate).getFullYear() >= currentYear;

    if (!isValidYear) {
      newDate = maxDate;
    }

    setSelectedDate(newDate);
    history.push(`/exchange-rates?date=${newDate}`);
  };

  /**
   * Ustawia `maxDate` na aktualną datę w przypadku, gdy rok jest mniejszy od aktualnego.
   */
  const setMaxDateIfNeeded = () => {
    if (!isValidYear) {
      setSelectedDate(maxDate);
      history.push(`/exchange-rates?date=${maxDate}`);
    }
  };

  // Efekt dla ustawienia `maxDate` przy pierwszym renderowaniu komponentu.
  useEffect(() => {
    setMaxDateIfNeeded();
  }, [isValidYear, maxDate, setSelectedDate]);

  return (
    <div className="d-flex flex-column align-items-center datePickerContainer mb-3">
      <label htmlFor="datePicker">Wybierz datę:</label>
      {/* Pole wejściowe do wybierania daty z obsługą zmiany. */}
      <input
        type="date"
        id="datePicker"
        value={selectedDate}
        onChange={handleChange}
        min={minDate}
        max={maxDate}
        className="form-control"
      />
    </div>
  );
};

export default DatePicker;
