import React, {useRef, useEffect, useState} from 'react';
import {useHistory} from 'react-router-dom';
import ApiResponseHelper from '../helper/ApiResponseHelper';
import '../../css/ExchangeRates.css';
import axiosInstance from '../axiosConfig/axiosInstance';
import Spinner from './Spinner';

const ExchangeRates = () => {
    const now = new Date();
    const today = now.toISOString().split('T')[0];
    const minDate = '2023-01-01';

    const currencyDateRef = useRef();
    const history = useHistory();

    const [exchangeRatesToday, setExchangeRatesToday] = useState([]);
    const [exchangeRatesByDate, setExchangeRatesByDate] = useState([]);
    const [loading, setLoading] = useState(true);

    const getDateToRequest = () => {
        let dateFromUrl = new URLSearchParams(history.location.search).get('date');
        dateFromUrl = dateFromUrl ? new Date(dateFromUrl).toISOString().split('T')[0] : '';

        return dateFromUrl;
    }

    const [selectedDate, setSelectedDate] = useState(getDateToRequest());

    const handleDateChange = (event) => {
        setSelectedDate(
            event.target.value.length > 0
                ? event.target.value
                : ''
        );
        history.push('?date=' + event.target.value);
    };

    useEffect(() => {
        setLoading(true);

        axiosInstance.get(
            '/api/exchange-rate-list?date=' + selectedDate
        ).then(response => {
            if (ApiResponseHelper.isSuccess(response?.code)) {
                setExchangeRatesToday(response.data.exchange_rates_today);
                setExchangeRatesByDate(response.data.exchange_rates_by_date);
            }
        }).catch(function (error) {
            console.log(error);
        });

        setLoading(false);
    }, [selectedDate]);

    if (loading) {
        return <Spinner/>;
    }

    return (
        <div className="m-5">
            <h3 className="text-center mt-5 mb-5">
                Board with exchange rates
            </h3>
            <div className="form-group d-flex align-items-center">
                <label htmlFor="exchangeRateDate" className="mb-0 pr-3">
                    Choose date
                </label>
                <input
                    id="exchangeRateDate"
                    name="exchangeRateDate"
                    type="date"
                    min={minDate}
                    max={today}
                    onChange={handleDateChange}
                    ref={currencyDateRef}
                    className="form-control w-25"
                    value={selectedDate}
                />
            </div>
            {loading ? (
                <Spinner/>
            ) : (
                <table className="table exchange-rates-table table-bordered my-5">
                    <thead>
                    <tr>
                        <th scope="col" rowSpan={2} className="text-center align-middle currency-th">
                            Currency name
                        </th>
                        <th scope="col" rowSpan={2} className="text-center align-middle currency-th">
                            Currency code
                        </th>
                        <th scope="col" colSpan={4} className="text-center current-date-th">
                            Current date
                        </th>
                        {exchangeRatesByDate.length > 0 && <>
                            <th scope="col" colSpan={4} className="text-center choosen-date-th">
                                Choosen date
                            </th>
                        </>}
                    </tr>
                    <tr>
                        <th className="text-center align-middle current-date-th">
                            Date
                        </th>
                        <th className="text-center align-middle current-date-th">
                            MID
                        </th>
                        <th className="text-center align-middle current-date-th">
                            BUY
                        </th>
                        <th className="text-center align-middle current-date-th">
                            SELL
                        </th>
                        {exchangeRatesByDate.length > 0 && <>
                            <th className="text-center align-middle choosen-date-th">
                                Date
                            </th>
                            <th className="text-center align-middle choosen-date-th">
                                MID
                            </th>
                            <th className="text-center align-middle choosen-date-th">
                                BUY
                            </th>
                            <th className="text-center align-middle choosen-date-th">
                                SELL
                            </th>
                        </>}
                    </tr>
                    </thead>
                    <tbody>
                    {exchangeRatesToday.length > 0
                        ? exchangeRatesToday.map((row, key) => (
                            <tr key={key}>
                                <td className="text-center align-middle bg-light">
                                    {row.fromFullname}
                                </td>
                                <td className="text-center align-middle bg-light">
                                    {row.from}
                                </td>
                                <td className="text-center align-middle bg-light">
                                    {new Date(row.date.date).toLocaleDateString()}
                                </td>
                                <td className="text-center align-middle bg-light">
                                    {row.mid ?? 0.00}
                                </td>
                                <td className="text-center align-middle bg-light">
                                    {row.buy ?? 'No buying'}
                                </td>
                                <td className="text-center align-middle bg-light">
                                    {row.sell ?? 'No selling'}
                                </td>
                                {exchangeRatesByDate.length > 0 && <>
                                    <td className="text-center align-middle bg-light">
                                        {new Date(exchangeRatesByDate[key].date.date).toLocaleDateString()}
                                    </td>
                                    <td className="text-center align-middle bg-light">
                                        {exchangeRatesByDate[key].mid ?? 0.00}
                                    </td>
                                    <td className="text-center align-middle bg-light">
                                        {exchangeRatesByDate[key].buy ?? 'No buying'}
                                    </td>
                                    <td className="text-center align-middle bg-light">
                                        {exchangeRatesByDate[key].sell ?? 'No selling'}
                                    </td>
                                </>}
                            </tr>
                        ))
                        : null}
                    </tbody>
                </table>
            )}
        </div>
    );
};

export default ExchangeRates;
