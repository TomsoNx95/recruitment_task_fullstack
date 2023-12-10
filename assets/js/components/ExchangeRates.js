import React, { useRef, useEffect, useState } from 'react';
import ApiResponseHelper from '../helper/ApiResponseHelper';
import '../../css/ExchangeRates.css';
import axiosInstance from '../axiosConfig/axiosInstance';

const ExchangeRates = () =>
{
    const now = new Date();
    const today = now.toISOString().substring(0, 10);

    const currencyDateRef = useRef();

    const [exchangeRatesToday, setExchangeRatesToday] = useState([]);
    const [exchangeRatesByDate, setExchangeRatesByDate] = useState([]);
    const [loading, setLoading] = useState(true);

    useEffect(() => {
        setLoading(true);

        axiosInstance.get(
            '/api/exchange-rate-list'
        ).then(response => {
            if (ApiResponseHelper.isSuccess(response?.code)) {
                setExchangeRatesToday(response.data.exchange_rates_today);
                setExchangeRatesByDate(response.data.exchange_rates_by_date);
            }
        }).catch(function (error) {
            console.log(error);
        });

        setLoading(false);
    }, []);

    return (
        <div className="m-5">
            <h3 className="text-center mt-5">
                Board with exchange rates
            </h3>
            <div className="form-group d-flex align-items-center">
                <label htmlFor="currencyDate" className="mb-0 pr-3">
                    Choose date
                </label>
                <input
                    id="exchangeRateDate"
                    name="exchangeRateDate"
                    type="date"
                    min="2023-01-01"
                    max={today}
                    // onChange={onDataChange}
                    ref={currencyDateRef}
                    className="form-control w-25"
                />
            </div>
            {loading ? (
                <div className={'text-center'}>
                    <span className="fa fa-spin fa-spinner fa-4x"></span>
                </div>
            ) : (
                <table className="table table-bordered my-5">
                    <thead>
                        <tr>
                            <th className="text-center align-middle">
                                Currency name
                            </th>
                            <th className="text-center align-middle">
                                Currency code
                            </th>
                            <th className="text-center align-middle">
                                Date
                            </th>
                            <th className="text-center align-middle">
                                MID
                            </th>
                            <th className="text-center align-middle">
                                BUY
                            </th>
                            <th className="text-center align-middle">
                                SELL
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        {exchangeRatesToday
                        ? exchangeRatesToday.map((row, key) => (
                            <tr key={key}>
                                <td className="text-center align-middle">
                                    { row.fromFullname }
                                </td>
                                <td className="text-center align-middle">
                                    { row.from }
                                </td>
                                <td className="text-center align-middle">
                                    { new Date(row.date.date).toLocaleDateString() }
                                </td>
                                <td className="text-center align-middle">
                                    { row.mid ?? 0.00 }
                                </td>
                                <td className="text-center align-middle">
                                    { row.buy ?? 'No buying' }
                                </td>
                                <td className="text-center align-middle">
                                    { row.sell ?? 'No selling' }
                                </td>
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
