import React from 'react';

const DatePicker = ({ selectedDate, onChange }) => {
  const currentDate = new Date();
  const minDate = `${currentDate.getFullYear()}-01-01`;
  const maxDate = currentDate.toISOString().split('T')[0].substring(0, 10);

  const handleChange = (event) => {
    const selectedDate = event.target.value;
    onChange(selectedDate);
  };

  return (
    <input
      type="date"
      value={selectedDate || maxDate}
      onChange={handleChange}
      min={minDate}
      max={maxDate}
    />
  );
};

export default DatePicker;