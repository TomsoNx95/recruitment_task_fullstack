import React, { useState } from 'react';

const DatePicker = () => {
  const currentDate = new Date();

  const year = currentDate.getFullYear();
  const month = (currentDate.getMonth() + 1).toString().padStart(2, '0');
  const day = currentDate.getDate().toString().padStart(2, '0');

  const minDate = `${year}-01-01`;
  const maxDate = `${year}-${month}-${day}`;

  const [selectedDate, setSelectedDate] = useState(maxDate);

  const handleChange = (event) => {
    const newDate = event.target.value;
    setSelectedDate(newDate);
  };

  return (
    <div className="d-flex flex-column align-items-center datePickerContainer mb-3 ">
      <label htmlFor="datePicker">Wybierz datę:</label>
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