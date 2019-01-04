<!-- Edit Reviews Modal-->

<div id="editReviewsModal" class="modal fade ali-modal ali-modal--noline ali-modal--form" role="dialog" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form id="formEditReview">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <i class="material-icons">close</i>
                        </button>
                    </div>
    
                    <div class="modal-body pad-b-0 pad-t-0 mar-b-20">
                        <div class="modal-body__content">
                            <div class="modal-body__title fz21 fw-bold color-dark-blue mar-b-25 text-center">{{ __('reviews.title_edit_review') }}</div>
                            <div class="row">
                                <div class="col-sm-3">
                                    <div class="wrapper-preview-img">
                                        <label for="preview-input">
                                            <img src="{{ asset('images/logo.png') }}" class="img-circle preview-result avatar">
                                            {{-- <input type="file" id="preview-input" class="hidden"> --}}
                                            {{-- <i class="material-icons">add</i> --}}
                                        </label>
                                    </div>
                                </div>

                                <div class="col-sm-9">
                                    <p class="title-note mar-b-10 ">{{ __('reviews.label_name') }}</p>
                                    <div class="form-group">
                                        <input type="text" name="author" value="" placeholder="Name" class="form-control mar-b-10">
                                        <div class="form-group rating-group" style="margin-left: -2px;">
                                            <input type="hidden" name="star" class="alr-rating-edit" data-filled="alr-icon-star" data-empty="alr-icon-star" data-fractions="1"/>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <p class="title-note mar-b-10 ">{{__('reviews.label_date')}}</p>
                                        <div class='date'>
                                            <input type="text" data-date-format="DD/MM/YYYY" class="form-control datetimepicker"  name="created_at" value="{{date('d/m/Y')}}">
                                        </div>
                                    </div>

                                </div>

                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <p class="title-note mar-b-10 ">{{__('reviews.label_country')}}</p>
                                        <select name="country" id="country" class="form-control" >
                                            @foreach($listAllCountry as $key=>$item)
                                                <option value="{{$key}}">{{$item}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <p class="title-note mar-b-10">{{ __('reviews.label_feedback') }}</p>
                                        <textarea name="content" placeholder="Your thinking" class="form-control vertical-resize" rows="5"></textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <p class="title-note" style="margin-bottom: -5px;">{{ __('reviews.label_photo') }}</p>
                                        <div class="wrapper-up-photo">
                                            <div class="pad-0 d-inline-block review-photo-list p-relative">
                                                <label class="button button--default wrapper-up-photo__button">
                                                    <i class="material-icons">add</i>
                                                    <input type="file" id="upload-review-photo" class="hidden up-photos-input" multiple="" accept="image/x-png,image/gif,image/jpeg">
                                                </label>

                                                <div class="ali_loading_upload" style="display: none; position: absolute;top: 0px;left: 0; width: 100%; height: 100%; background: rgba(69, 108, 179, 0.09);  z-index: 1;">
                                                    <img src="{{ cdn('images/loading-small.svg') }}" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
    
                    <div class="modal-footer text-center">
                        <button type="submit" class="button button--primary w-130px mar-r-5">Save</button>
                        <button type="button" class="button button--default w-130px mar-l-5" data-dismiss="modal">Cancel</button>
                    </div>
    
                    <input type="hidden" value="0" name="comment_id">
                    <input type="hidden" value="{{ csrf_token() }}" name="_token">
                </form>
            </div>
        </div>
    </div>

