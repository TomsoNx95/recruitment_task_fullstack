import React, { useRef, useEffect, useState } from 'react';
import ApiResponseHelper from '../helper/ApiResponseHelper';
import axios from 'axios';
import '../../css/ExchangeRates.css';

const ExchangeRates = () =>
{
    const now = new Date();
    const today = now.toISOString().substring(0, 10);

    const currencyDateRef = useRef();

    const [exchangeRates, setExchangeRates] = useState([]);

    useEffect(() => {
        axios.get(
            'http://telemedi-zadanie.localhost/api/exchange-rate-list'
        ).then(response => {
            if (ApiResponseHelper.isSuccess(response?.data?.code)) {
                setExchangeRates(response.data.data.exchange_rates_today);
            }
        }).catch(function (error) {
            console.log(error);
        });
    }, []);

    return (
        <>
            <h3 className="text-center mt-5">
                Board with exchanges rates
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
            <table className="table table-bordered my-5">
                <thead>
                    <tr>
                        <th scope="col"  className="text-center align-middle">
                            Currency name
                        </th>
                        <th scope="col" className="text-center align-middle">
                            Currency code
                        </th>
                        <th scope="col" className="text-center align-middle">
                            Date
                        </th>
                        <th scope="col" className="text-center align-middle">
                            MID
                        </th>
                        <th scope="col" className="text-center align-middle">
                            BUY
                        </th>
                        <th scope="col" className="text-center align-middle">
                            SELL
                        </th>
                    </tr>
                </thead>
                <tbody>
                    {exchangeRates
                    ? exchangeRates.map((row, key) => (
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
        </>
    );
};

export default ExchangeRates;
