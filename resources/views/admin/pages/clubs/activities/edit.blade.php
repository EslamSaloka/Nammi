@extends('admin.layouts.master')
@section('title',$breadcrumb['title'])
@section('PageContent')

<div class="row row-sm">
    <div class="col-lg-12 col-md-12">
        <div class="card custom-card">
            @if(isset($activity))
                <form action="{{ route('admin.clubs.activities.update', $activity->id) }}" method="post" enctype="multipart/form-data">
            @method('PUT')
            @else
                <form action="{{ route('admin.clubs.activities.store') }}" method="post" enctype="multipart/form-data">
            @endif

                <div class="card-body">
                    <div class="row">


                        <div class="col-md-12">
                            <div class="form-group">
                                @include('admin.component.inc.lang', [
                                    'data' => \App\Models\Activity::LOCALIZATION_INPUTS,
                                    'model' => isset($activity) ? $activity : null,
                                    'group_key' => 'activities'
                                ])
                            </div>
                        </div>


                        @if (isAdmin())
                            <div class="col-md-6">
                                @include('admin.component.form_fields.select', [
                                    'label' => 'Club',
                                    'name' => 'club_id',
                                    'data'  => $clubs,
                                    'required' => true,
                                    'keyV'      => (App::getLocale() == "ar") ? "name" : "name_en",
                                    'value' => old('club_id') ?? (isset($activity) ? $activity->club_id : null)
                                    ])
                            </div>

                            <div class="col-md-6" id="menus">
                                @if (isset($activity))
                                    @include('admin.component.form_fields.select', [
                                        'label' => 'Branch',
                                        'name' => 'branch_id',
                                        'data'  => \App\Models\Club\Branch::where("club_id",$activity->club_id)->get(),
                                        'required' => true,
                                        'value' => $activity->branch_id
                                    ])
                                @endif
                            </div>
                            <div class="col-md-6" id="menus2">
                                @if (isset($activity))
                                    @include('admin.component.form_fields.select', [
                                        'label'     => "Main Category",
                                        'name'      => 'main_category',
                                        'data'      => \App\Models\Category::whereIn("id",$activity->club->categories()->where("parent_id","=",0)->pluck("category_id")->toArray())->get(),
                                        'required'  => true,
                                        'value'     => $activity->categories()->where("parent_id","=",0)->pluck("category_id")->toArray()
                                    ])
                                @endif
                            </div>
                            <div class="col-md-6" id="menus22">
                                @if (isset($activity))
                                    @include('admin.component.form_fields.select', [
                                        'label'     => "Sub Category",
                                        'name'      => 'sub_category',
                                        'data'      => \App\Models\Category::whereIn("parent_id",$activity->categories()->where("parent_id","=",0)->pluck("category_id")->toArray())->get(),
                                        'required'  => true,
                                        'value'     => $activity->categories()->where("parent_id","!=",0)->pluck("category_id")->toArray()
                                    ])
                                @endif
                            </div>
                        @else
                            <div class="col-md-12" id="menus">
                                @include('admin.component.form_fields.select', [
                                    'label' => 'Branch',
                                    'name' => 'branch_id',
                                    'data'  => \App\Models\Club\Branch::where("club_id",\Auth::user()->id)->get(),
                                    'required' => true,
                                    'value' => (isset($activity)) ? $activity->branch_id : null
                                ])
                            </div>


                            <div class="col-md-6" id="menus2">
                                @if (isset($activity))
                                    @include('admin.component.form_fields.select', [
                                        'label'     => "Main Category",
                                        'name'      => 'main_category',
                                        'data'      => \App\Models\Category::whereIn("id",\Auth::user()->categories()->where("parent_id","=",0)->pluck("category_id")->toArray())->get(),
                                        'required'  => true,
                                        'value'     => $activity->categories()->where("parent_id","=",0)->pluck("category_id")->toArray()
                                    ])
                                @else
                                    @include('admin.component.form_fields.select', [
                                        'label'     => "Main Category",
                                        'name'      => 'main_category',
                                        'data'      => \App\Models\Category::whereIn("id",\Auth::user()->categories()->where("parent_id","=",0)->pluck("category_id")->toArray())->get(),
                                        'required'  => true,
                                        'value'     => null
                                    ])
                                @endif
                            </div>
                            <div class="col-md-6" id="menus22">
                                @if (isset($activity))
                                    @include('admin.component.form_fields.select', [
                                        'label'     => "Sub Category",
                                        'name'      => 'sub_category',
                                        'data'      => \App\Models\Category::whereIn("parent_id",$activity->categories()->where("parent_id","=",0)->pluck("category_id")->toArray())->get(),
                                        'required'  => true,
                                        'value'     => $activity->categories()->where("parent_id","!=",0)->pluck("category_id")->toArray()
                                    ])
                                @endif
                            </div>


                        @endif

                        <div class="col-md-6">
                            @include('admin.component.form_fields.input', [
                                'label' => 'price',
                                'name' => 'price',
                                'placeholder' => 'price',
                                'type' => 'text',
                                'required' => true,
                                'value' => old('price') ??(isset($activity) ? $activity->price : null)
                            ])
                        </div>
                        <div class="col-md-6">
                            @include('admin.component.form_fields.input', [
                                'label' => 'Offer Price',
                                'name' => 'offer',
                                'placeholder' => 'Enter offer',
                                'type' => 'text',
                                'required' => true,
                                'value' => old('offer') ??(isset($activity) ? $activity->offer : 0)
                            ])
                        </div>
                        <div class="col-md-6">
                            @include('admin.component.form_fields.input', [
                                'label' => 'Offer Start Date',
                                'name' => 'start_offer',
                                'placeholder' => 'Enter start_offer',
                                'type' => 'date',
                                'required' => true,
                                'value' => old('start_offer') ??(isset($activity) ? $activity->start_offer : null)
                            ])
                        </div>
                        <div class="col-md-6">
                            @include('admin.component.form_fields.input', [
                                'label' => 'Offer End Date',
                                'name' => 'end_offer',
                                'placeholder' => 'Enter end_offer',
                                'type' => 'date',
                                'required' => true,
                                'value' => old('end_offer') ??(isset($activity) ? $activity->end_offer : null)
                            ])
                        </div>

                        <div class="col-md-6">
                            @include('admin.component.form_fields.input', [
                                'label' => 'Customer Count',
                                'name' => 'customer_count',
                                'placeholder' => 'Enter customer_count',
                                'type' => 'number',
                                'required' => true,
                                'value' => old('customer_count') ??(isset($activity) ? $activity->customer_count : null)
                            ])
                        </div>

                        <div class="col-md-6">
                            @include('admin.component.form_fields.select', [
                                'label' => 'Disabilities',
                                'name' => 'disabilities',
                                'type' => 'text',
                                'required' => true,
                                "data"      => [
                                    (object)[
                                        "id"    => 0,
                                        "name"  => "No",
                                    ],
                                    (object)[
                                        "id"    => 1,
                                        "name"  => "Yes",
                                    ],
                                ],
                                'value' => old('disabilities') ??(isset($activity) ? $activity->disabilities : null)
                            ])
                        </div>

                        <div class="col-md-6">
                            @include('admin.component.form_fields.select', [
                                'label'     => 'Payment Types',
                                'name'      => 'payment_types[]',
                                'required'  => true,
                                'multiple'  => true,
                                "data"      => [
                                    (object)[
                                        "id"    => "cod",
                                        "name"  => "cod",
                                    ],
                                    (object)[
                                        "id"    => "visa",
                                        "name"  => "visa",
                                    ],
                                ],
                                'value'     =>  (isset($activity) ? $activity->payment_types : null)
                            ])
                        </div>

                        <div class="col-md-6">
                            @include('admin.component.form_fields.select', [
                                'label'     => 'Order One Time',
                                'name'      => 'order_one_time',
                                'required'  => true,
                                "data"      => [
                                    (object)[
                                        "id"    => 0,
                                        "name"  => "No",
                                    ],
                                    (object)[
                                        "id"    => 1,
                                        "name"  => "Yes",
                                    ],
                                ],
                                'value'     => old('order_one_time') ??(isset($activity) ? $activity->order_one_time : null)
                            ])
                        </div>

                        <div class="col-md-12">
                            @include('admin.component.form_fields.image', [
                                'label' => 'Image',
                                'name' => 'image',
                                'value' => old('img_base64') ?? (isset($activity) ? $activity->display_image : null)
                            ])
                        </div>


                    </div>
                </div>
                <div class="card-footer mb-1">
                    @csrf
                    <button type="submit" class="btn btn-primary">@lang('Submit')</button>
                    <a href="{{ route('admin.clubs.index')}}" class="btn btn-danger">@lang('Cancel')</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@push('scripts')
    <script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>
    @if (isset($activity))
        {!! JsValidator::formRequest('App\Http\Requests\Dashboard\Clubs\Activities\UpdateRequest') !!}
    @else
        {!! JsValidator::formRequest('App\Http\Requests\Dashboard\Clubs\Activities\CreateRequest') !!}
    @endif
    @if (isAdmin())
        <script>
            $('#club_id').change(function(){
                $.get("{{ route('admin.clubs.branches.getAll') }}?club="+$(this).val(), function(data, status){
                    $("#menus").html(data);
                    $('.select2').select2();
                }).then((result) => {
                    $.get("{{ route('admin.categories.getAll') }}?club="+$(this).val(), function(data, status){
                        $("#menus2").html(data);
                        $('.select2').select2();
                    });
                });
            });
        </script>
    @else
        <script>
            $.get("{{ route('admin.categories.getAll') }}?club="+$(this).val(), function(data, status){
                $("#menus2").html(data);
                $('.select2').select2();
            });

        </script>
    @endif
    <script>
        $(document).on("change","#main_category",function(){
            $.get("{{ route('admin.categories.child') }}?cat="+$(this).val(), function(data, status){
                $("#menus22").html(data);
                $('.select2').select2();
            });
        });
    </script>
@endpush
