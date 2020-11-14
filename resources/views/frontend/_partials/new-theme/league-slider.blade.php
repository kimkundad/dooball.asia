<div class="league-box">
    <div class="row">
        <div class="col-lg-3 col-md-3 col-sm-3 col-4 e-left d-flex alit-center just-center">
            <img src="{{ asset('images/dooball-tarm-league.png') }}" class="dbt-league">
        </div>
        <div class="col-lg-9 col-md-9 col-sm-9 col-8 e-right slide-area flex-center">
            <!-- d-flex alit-center -->
            <div class="slide-prev flex-center">
                <button class="btn" type="button" onclick="prevSlide()">
                    <i class="fa fa-chevron-left"></i>
                </button>
            </div>
            <div class="slide-outmost">
                <div class="slide-cover"></div>
            </div>
            <div class="slide-next flex-center">
                <button  class="btn" type="button" onclick="nextSlide()">
                    <i  class="fa fa-chevron-right"></i>
                </button>
            </div>
        </div>
    </div>
</div>
<input type="hidden" id="mock_one" value="{{ asset('images/league/mock-slider-01.png') }}">
<input type="hidden" id="mock_two" value="{{ asset('images/league/mock-slider-02.png') }}">
<input type="hidden" id="mock_three" value="{{ asset('images/league/mock-slider-01.png') }}">
<input type="hidden" id="mock_four" value="{{ asset('images/league/mock-slider-02.png') }}">
<input type="hidden" id="mock_five" value="{{ asset('images/league/mock-slider-01.png') }}">