/* ------------------------------------
    Campaign List Custom Javascript
------------------------------------ */

let URL = $('meta[name="base-path"]').attr('content');

'use strict';
$(document).ready(function() {
    setTimeout(function() {
        // [ Gauge-chart ] start
        var dom = document.getElementById("chart-gauge-productivity");
        var myChart = echarts.init(dom);
        var app = {};
        var option = null;
        option = {
            tooltip: {
                formatter: "{a} <br/>{b} : {c}%"
            },
            series: [{
                name: 'gauge Chart',
                type: 'gauge',
                axisLine: {
                    show: true,
                    lineStyle: {
                        color: [
                            [0.8, '#FF0000'],
                            [1, '#00A400']
                        ],
                        width: 10
                    }
                },
                detail: {
                    formatter: '{value}%'
                },
                data: [{
                    value: 50,
                    name: ''
                }]
            }]
        };
        if (option && typeof option === "object") {
            myChart.setOption(option, true);
        }
        // [ Gauge-chart ] end

        var dom2 = document.getElementById("chart-gauge-quality");
        var myChart2 = echarts.init(dom2);
        var app2 = {};
        var option2 = null;
        option2 = {
            tooltip: {
                formatter: "{a} <br/>{b} : {c}%"
            },
            series: [{
                // min: 0,
                // max: 240,
                // splitNumber: 12,
                name: 'gauge Chart',
                type: 'gauge',
                axisLine: {
                    show: true,
                    lineStyle: {
                        color: [
                            [0.8, '#FF0000'],
                            [1, '#00A400']
                        ],
                        width: 10
                    }
                },
                detail: {
                    formatter: '{value}%'
                },
                data: [{
                    value: 100,
                    name: ''
                }]
            }]
        };
        if (option2 && typeof option2 === "object") {
            myChart2.setOption(option2, true);
        }

        var dom7 = document.getElementById("bar-counts-by-work-type");
        var myChart7 = echarts.init(dom7);
        var app7 = {};

        var option7 = null;

        option7 = {
            xAxis: {
                type: 'category',
                data: ['CD', 'CDQA', 'ABM', 'Lead Nurture', 'Address Fetch']
            },
            yAxis: {
                type: 'value'
            },
            series: [
                {
                    data: [
                        {
                            value: 50,
                            itemStyle: {
                                color: '#ff598f'
                            }
                        },
                        {
                            value: 100,
                            itemStyle: {
                                color: '#fd8a5e'
                            }
                        },
                        {
                            value: 200,
                            itemStyle: {
                                color: '#e0e300'
                            }
                        },
                        {
                            value: 75,
                            itemStyle: {
                                color: '#01dddd'
                            }
                        },
                        {
                            value: 50,
                            itemStyle: {
                                color: '#00bfaf'
                            }
                        },
                    ],
                    type: 'bar'
                }
            ]
        };

        if (option7 && typeof option7 === 'object') {
            myChart7.setOption(option7);
        }


        var dom8 = document.getElementById("bar-leads-generated-monthly");
        var myChart8 = echarts.init(dom8);
        var app8 = {};

        var option8 = null;

        option8 = {
            tooltip: {
                trigger: 'axis',
                axisPointer: {
                    type: 'shadow',
                    label: {
                        show: true
                    }
                }
            },
            calculable: true,
            grid: {
                top: '12%',
                left: '1%',
                right: '10%',
                containLabel: true
            },
            xAxis: [
                {
                    type: 'category',
                    data: [1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31]
                }
            ],
            yAxis: [
                {
                    type: 'value',
                    name: 'Budget'
                }
            ],
            series: [
                {
                    name: 'Budget 2011',
                    type: 'bar',
                    data: [
                        120,
                        200,
                        150,
                        80,
                        70,
                        120,
                        200,
                        150,
                        80,
                        70,
                        120,
                        200,
                        150,
                        80,
                        70,
                        120,
                        200,
                        150,
                        80,
                        70,
                        120,
                        200,
                        150,
                        80,
                        70,
                        120,
                        200,
                        150,
                        80,
                        70,
                        15

                    ],
                    color: '#3C66E9'
                }
            ]
        };

        if (option8 && typeof option8 === 'object') {
            myChart8.setOption(option8);
        }

    }, 700);
});

