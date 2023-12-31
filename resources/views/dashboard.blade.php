@extends('layouts.no-sidebar')

@section('css')
<style>
    .icon-card {
        color: #3a57e8;
    }
    .text-dark {
        color: #32333b !important;
    }
    .card.layanan:hover {
        transition: all 0.3s ease-in-out;
        -webkit-box-shadow: 0 10px 30px 0 rgb(17 38 146 / 20%);
        box-shadow: 0 10px 30px 0 rgb(17 38 146 / 20%);
        margin-bottom: 2rem;
    
    }
</style>
@endsection

@section('content')
    <div class="row">
        <div class="col-xl-4 col-lg-6 col-md-6 col-xs-12">
            <div class="card" onclick="fiterByLayanan(1)" style="cursor: pointer;">
                <div class="card-body d-flex align-items-center" style="height: 120px;">
                    <div class="me-3 text-primary">
                        <svg width="48" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="color: red;">                                
                            <path opacity="0.4" d="M16.191 2H7.81C4.77 2 3 3.78 3 6.83V17.16C3 20.26 4.77 22 7.81 22H16.191C19.28 22 21 20.26 21 17.16V6.83C21 3.78 19.28 2 16.191 2Z" fill="currentColor"></path>                                
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M8.07996 6.6499V6.6599C7.64896 6.6599 7.29996 7.0099 7.29996 7.4399C7.29996 7.8699 7.64896 8.2199 8.07996 8.2199H11.069C11.5 8.2199 11.85 7.8699 11.85 7.4289C11.85 6.9999 11.5 6.6499 11.069 6.6499H8.07996ZM15.92 12.7399H8.07996C7.64896 12.7399 7.29996 12.3899 7.29996 11.9599C7.29996 11.5299 7.64896 11.1789 8.07996 11.1789H15.92C16.35 11.1789 16.7 11.5299 16.7 11.9599C16.7 12.3899 16.35 12.7399 15.92 12.7399ZM15.92 17.3099H8.07996C7.77996 17.3499 7.48996 17.1999 7.32996 16.9499C7.16996 16.6899 7.16996 16.3599 7.32996 16.1099C7.48996 15.8499 7.77996 15.7099 8.07996 15.7399H15.92C16.319 15.7799 16.62 16.1199 16.62 16.5299C16.62 16.9289 16.319 17.2699 15.92 17.3099Z" fill="currentColor"></path>                                
                        </svg>                                                       
                    </div>
                    <div>
                        <h5 class="mb-0 fw-bold mb-1" style="font-size: 18px;">Survey Kepuasan</h5>
                        <h4 class="mb-0 fw-bold text-muted">{{ $total_ak1 }}</h4>
                        <p class="mb-0 text-muted">Mengikuti Survey</p>
                    </div>
                </div>
            </div>
        </div>
    <div class="row mt-5 mb-5" id="layanan">
        <div class="col-12 mb-4">
            <h2 class="text-center">Survey Kepuasan Masyarakat</h2>
            <p class="text-center">Pilih survey dibawah ini</p>
        </div>

        <div class="col-xl-4 col-lg-4 col-md-6 col-xs-12">
            <a href="{{ url('/kuesioner/ak1') }}">
                <div class="card card-body layanan">
                    <div class="text-center">
                        <svg width="32" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="color: red;">                                
                            <path opacity="0.4" d="M16.191 2H7.81C4.77 2 3 3.78 3 6.83V17.16C3 20.26 4.77 22 7.81 22H16.191C19.28 22 21 20.26 21 17.16V6.83C21 3.78 19.28 2 16.191 2Z" fill="currentColor"></path>                                
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M8.07996 6.6499V6.6599C7.64896 6.6599 7.29996 7.0099 7.29996 7.4399C7.29996 7.8699 7.64896 8.2199 8.07996 8.2199H11.069C11.5 8.2199 11.85 7.8699 11.85 7.4289C11.85 6.9999 11.5 6.6499 11.069 6.6499H8.07996ZM15.92 12.7399H8.07996C7.64896 12.7399 7.29996 12.3899 7.29996 11.9599C7.29996 11.5299 7.64896 11.1789 8.07996 11.1789H15.92C16.35 11.1789 16.7 11.5299 16.7 11.9599C16.7 12.3899 16.35 12.7399 15.92 12.7399ZM15.92 17.3099H8.07996C7.77996 17.3499 7.48996 17.1999 7.32996 16.9499C7.16996 16.6899 7.16996 16.3599 7.32996 16.1099C7.48996 15.8499 7.77996 15.7099 8.07996 15.7399H15.92C16.319 15.7799 16.62 16.1199 16.62 16.5299C16.62 16.9289 16.319 17.2699 15.92 17.3099Z" fill="currentColor"></path>                                
                        </svg>                            
                    </div>
                    <div class="fw-bold fs-5 mt-2 text-center text-dark">
                        @if($setting == null)
                        -
                        @else
                        {{ $setting->tahun }}<br>
                        Survei Ke - {{ $setting->survei_ke }}
                        @endif
                    </div>
                </div>
            </a>
        </div>
    </div>
@endsection

@section('script')
<script>
function rupiah(number) {
    const formatNumbering = new Intl.NumberFormat("id-ID", {minimumFractionDigits: 0});
    return formatNumbering.format(number)
}

async function fiterByLayanan(id) {
    const url = "{{ url('/') }}" + '/' + `dashboard-filter-layanan/${id}`;
    const response = await fetch(url);
    const json = await response.json();
    document.querySelector('#total_bulan_ini').innerHTML = json.total_bulan_ini;
    document.querySelector('#total_bulan_sebelumnya').innerHTML = json.total_bulan_sebelumnya;
}

var options = {
        series:  [
                {
                    name: 'Setting',
                    data: [0,0,0,0,0,0]
                }, 
            ],
        chart: {
            height: 350,
            type: 'bar',
            toolbar: {
                show: false
            }
        },
        
        stroke: {
            curve: 'smooth',
            width: 1.5
        },
        xaxis: {
            show: false,
            categories: ["AK1", "Rekom Passport", "Pelatihan", "LPK", "Pencatatan Perusahaan", "Perselisihan Hub Industrial"]
        },

        // tooltip: {
        //     x: {
        //     format: 'dd/MM/yy HH:mm'
        //     },
        // },
        colors: [ // this array contains different color code for each data
            "#FF0000",
            "#0bdd43",
            "#d900ff",
            "#3a57e8",
            "#000480",
            "#4e563b",
        ],
        fill: {
            colors: [
                "#FF0000",
                "#0bdd43",
                "#d900ff",
                "#3a57e8",
                "#000480",
                "#4e563b",
            ],
            gradient: {
                opacityFrom: 0.6,
                opacityTo: 0.1
            }
        },
        plotOptions: {
            bar: {
                distributed: true, // different color each bar
                columnWidth: '60%',
                borderRadius: 2,
                dataLabels: {
                    orientation: 'horizontal',
                    position: 'top',
                    minItem: 0,
                    hideOverflowingLabels: true,
                }
            },
        },
        dataLabels: {
            offsetY: -18,
            style: {
            colors: ['#222'],
            fontWeight: 'normal'
            },
            formatter: function (val, opts) {
                return rupiah(val);
            },
        },
        legend: {
            position: 'top',
            fontSize: '16px',
            itemMargin: {
                vertical: 10,
                horizontal: 10
            }
        }
    };

    var chart = new ApexCharts(document.querySelector("#chart"), options);

function fetchDataGraphic() {
    const url = "{{ url('/') }}" + '/' + `data-grafik-bar`;
    fetch(url)
        .then(response => response.json())
        .then(json => {
            chart.updateSeries(json);
        });
}

$(document).ready(function() {
    chart.render();

    fetchDataGraphic();
});


</script>
@endsection