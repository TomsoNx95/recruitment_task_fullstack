import React, { Component, useRef  } from 'react';
import axios from 'axios';
import '../../css/ExchangeRates.css';

const ExchangeRates = () =>
{
    const now = new Date();
    const today = now.toISOString().substring(0, 10);

    const currencyDateRef = useRef();

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
                    id="currencyDate"
                    name="currencyDate"
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
                    <th scope="col" rowSpan={3} className="text-center align-middle">
                        Date
                    </th>
                    <th scope="col" rowSpan={3} className="text-center align-middle">
                        Currency name
                    </th>
                    <th scope="col" colSpan={2} className="text-center align-middle">
                        Currency code
                    </th>
                    <th scope="col" colSpan={2} className="text-center align-middle">
                        MID
                    </th>
                    <th scope="col" colSpan={2} className="text-center align-middle">
                        BUY
                    </th>
                    <th scope="col" colSpan={2} className="text-center align-middle">
                        SELL
                    </th>
                </tr>
                </thead>
                {/*<tbody>*/}
                {/*{currencyTable*/}
                {/*    ? currencyTable.map((row, key) => (*/}
                {/*        <tr key={key}>*/}
                {/*            <td>{row.name}</td>*/}
                {/*            <td className="text-center">{row.code}</td>*/}
                {/*            <td className="text-center bg-light">*/}
                {/*                {formatCurrency(row.past.bank)}*/}
                {/*            </td>*/}
                {/*            <td className="text-center bg-light">*/}
                {/*                {formatCurrency(row.past.buy)}*/}
                {/*            </td>*/}
                {/*            <td className="text-center bg-light">*/}
                {/*                {formatCurrency(row.past.sell)}*/}
                {/*            </td>*/}
                {/*            <td className="text-center">*/}
                {/*                {formatCurrency(row.current.bank)}*/}
                {/*            </td>*/}
                {/*            <td className="text-center">*/}
                {/*                {formatCurrency(row.current.buy)}*/}
                {/*            </td>*/}
                {/*            <td className="text-center">*/}
                {/*                {formatCurrency(row.current.sell)}*/}
                {/*            </td>*/}
                {/*        </tr>*/}
                {/*    ))*/}
                {/*    : null}*/}
                {/*</tbody>*/}
            </table>
        </>
    );
    // constructor() {
    //     super();
    //     this.state = { loading: false };
    // }

    // render() {
    //     const loading = this.state.loading;
    //     return (
    //         <div>
    //             <section className="row-section">
    //                 <div className="container">
    //                     <div className="row mt-5">
    //                         <div className="col-md-8 offset-md-2">
    //                             {loading ? (
    //                                 <div className={'text-center'}>
    //                                     <span className="fa fa-spin fa-spinner fa-4x"></span>
    //                                 </div>
    //                             ) : (
    //                                 <p>Siema</p>
    //                             )}
    //                         </div>
    //                     </div>
    //                 </div>
    //             </section>
    //         </div>
    //     );
    // };
};

export default ExchangeRates;
