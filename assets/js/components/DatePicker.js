import React from 'react';
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
  // Pobranie aktualnej daty dla ustawienia minimalnej i maksymalnej daty w polu wejściowym 'date'.
  const currentDate = new Date();
  const year = currentDate.getFullYear();
  const month = (currentDate.getMonth() + 1).toString().padStart(2, '0');
  const day = currentDate.getDate().toString().padStart(2, '0');

  const minDate = `${year}-01-01`;
  const maxDate = `${year}-${month}-${day}`;

  // Pobranie obiektu historii routingu z React Router.
  const history = useHistory();

  /**
   * Obsługuje zmianę daty w polu wejściowym i aktualizuje stan oraz adres URL.
   * Jeśli data zostanie wyczyszczona, ustawia na maksymalną dostępną datę.
   *
   * @param {Object} event - Obiekt zdarzenia zmiany wartości pola wejściowego.
   */
  const handleChange = (event) => {
    let newDate = event.target.value;
    // Jeśli data zostanie wyczyszczona, ustaw maksymalną dostępną datę.
    if (newDate === '' || newDate === undefined ) {
      newDate = maxDate;
    }
    // Ustaw nową datę za pomocą funkcji przekazanej przez właściwość.
    setSelectedDate(newDate);
    // Zaktualizuj adres URL, aby uwzględnić nową datę.
    history.push(`/exchange-rates?date=${newDate}`);
  };

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
