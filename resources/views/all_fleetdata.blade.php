@extends('layouts.app')
@section('main-content')
    <div class="container-fluid py-2">
        <div class="row">
            <div class="col-12">
                
                <div class="row">
                    <div class="col">
                        <div class="card mb-4">
                            <div class="card-header p-4">
                                <h3>Feet Data</h3>
                            </div>
                            <div class="card-body p-0" style="box-shadow: 0px 5px 10px lightblue;">
                                <div class="table-responsive">
                                    <table style="width: 100%;" class="table align-items-center mb-0 table-bordered table-hover"
                                        id="credit-table">
                                        <thead class="table-active">
                                            <tr>
                                                <th
                                                    class="text-uppercase text-secondary text-xs text-center opacity-7 ps-2">
                                                    Id
                                                </th>
                                                <th
                                                    class="text-uppercase text-secondary text-xs text-center opacity-7 ps-2">
                                                    Location
                                                </th>
                                                <th
                                                    class="text-uppercase text-secondary text-xs text-center opacity-7 ps-2" style="width:350px!important;">
                                                    Door No.
                                                </th>
                                                <th
                                                    class="text-uppercase text-secondary text-xs text-center opacity-7 ps-2">
                                                    Total Cost
                                                </th>
                                                @foreach($header_arr as $val)
                                                <th
                                                    class="text-uppercase text-secondary text-xs text-center opacity-7 ps-2">
                                                    {{$val}}
                                                </th>
                                                @endforeach
                                               
                                            </tr>
                                        </thead>
                                        <tbody class="scrollable-tbody">
                                            @if($alldata)
                                                @foreach($alldata as $detail)
                                                <tr>
                                                    <td>{{ $detail->id }}</td>
                                                    <td>{{ $detail->location }}</td>
                                                    
                                                    <td style="width:350px!important;"><div style="word-wrap: break-word;">
                                                    {{ $detail->door_no }}</div></td>
                                                    
                                                    <td>{{ $detail->total_cost }}</td>
                                                    @foreach($header_arr as $val)
                                                        <td>
                                                        @if($val == $detail->category_name)
                                                            {{ $detail->category_amount}}
                                                        @else
                                                            -
                                                        @endif
                                                        </td>
                                                        
                                                    @endforeach
                                                    
                                                </tr>
                                                @endforeach
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection
@push('script')
   
@endpush
