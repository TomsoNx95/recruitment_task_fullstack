import React, { Component } from 'react';
import axios from 'axios';

class ExchangeRates extends Component
{
    constructor() {
        super();
        this.state = { loading: false };
    }

    render() {
        const loading = this.state.loading;
        return (
            <div>
                <section className="row-section">
                    <div className="container">
                        <div className="row mt-5">
                            <div className="col-md-8 offset-md-2">
                                {loading ? (
                                    <div className={'text-center'}>
                                        <span className="fa fa-spin fa-spinner fa-4x"></span>
                                    </div>
                                ) : (
                                    <p>Siema</p>
                                )}
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        );
    };
}

export default ExchangeRates;
