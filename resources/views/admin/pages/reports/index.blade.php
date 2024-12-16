@extends('admin.layouts.master')
@section('title',$breadcrumb['title'])
@section('PageContent')

@section('buttons')
    @include('admin.component.buttons.filter')
@endsection

<div class="row row-sm">
    <div class="col-sm-12 col-lg-12 col-xl-8">
        <div class="card custom-card">
            <div class="card-body">
                <h3>
                    @lang('System Reports') - {{ $year }}
                </h3>
                <canvas id="myChart"></canvas>
            </div>
        </div>
    </div>
    <div class="col-sm-12 col-lg-12 col-xl-4">
        <div class="card custom-card">
            <div class="card-body">
                <h3>
                    @lang('Orders Status') - {{ $year }}
                </h3>
                <canvas id="myPie"></canvas>
            </div>
        </div>
    </div>
</div>

@include('admin.component.modals.filter', [
    'fields' => [
        [
            'name' => 'year',
            'label' => 'Search by Year',
            'type' => 'year'
        ],
    ],
    'url' => route('admin.reports.index')
])


@endsection
@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx  = document.getElementById('myChart');
        const ctxp = document.getElementById('myPie');

        new Chart(ctx, {
          type: 'bar',
          data: {
            labels: ['01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12'],
            datasets: [{
              label: 'Customers',
              data: {!! $customerBar !!},
              borderWidth: 1
            },{
              label: 'Clubs',
              data: {!! $clubsBar !!},
              borderWidth: 1
            },
            {
              label: 'Orders',
              data: {!! $ordersBar !!},
              borderWidth: 1
            }]
          },
          options: {
            scales: {
              y: {
                beginAtZero: true
              }
            }
          }
        });

        new Chart(ctxp, {
          type: 'polarArea',
          data: {
            labels: {!! $orderStatus !!},
            datasets: [{
                data: {!! $orderStatusFData !!},
                hoverOffset: 4
            }]
          }
        });
    </script>
@endpush
