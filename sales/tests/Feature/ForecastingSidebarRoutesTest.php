<?php

it('defines the forecasting submenu routes', function () {
    $this->assertStringEndsWith('/forecasting/reports', route('forecasting.reports'));
    $this->assertStringEndsWith('/forecasting/performance', route('forecasting.performance'));
    $this->assertStringEndsWith('/forecasting/forecast', route('forecasting.forecast'));
    $this->assertStringEndsWith('/forecasting/recommendations', route('forecasting.recommendations'));
});
