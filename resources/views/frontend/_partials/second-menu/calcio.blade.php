<li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownSubMenu" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        กัลโซ่ <span class="sr-only">(current)</span>
    </a>
    <?php // <a href='/about'>About</a>, <a href='{{ route('about') }}'>About</a>,<a href='{!! url('/about'); !!}'>About</a> ?>
    <div class="dropdown-menu first-level" aria-labelledby="navbarDropdownSubMenu">
        <div class="dropdown-header d-flex alit-center">
            <img src="{{ asset('images/league/icon-calcio.png') }}" width="30">
            &nbsp;&nbsp;กัลโซ่ 2019 - 2020
        </div>
        <a class="dropdown-item" href="#">กราฟราคาบอลไหลกัลโซ่</a>
        <a class="dropdown-item" href="{{ route('analysis') }}">วิเคราะห์บอลกัลโซ่</a>
        <a class="dropdown-item" href="{!! url('/dooball') !!}">ดูบอลกัลโซ่</a>
        <a class="dropdown-item" href="{!! url('/highlights') !!}">ไฮไลท์กัลโซ่</a>
        <a class="dropdown-item this-is-show-sub" href="{!! url('/score') !!}">
            <div class="dropdown-submenu">
                <a href="{!! url('/score') !!}" class="text-link-sub d-flex alit-center just-between dropdown-toggle" data-toggle="dropdown">ผลบอลกัลโซ่</a>
                <ul class="dropdown-menu">
                    <li><a href="#" class="d-flex alit-center">ผลบอลเมื่อวาน</a></li>
                </ul>
            </div>
        </a>
        <a class="dropdown-item" href="{!! url('/odds') !!}">บอลไหล</a>
        <a class="dropdown-item this-is-show-sub" href="{!! url('/programs') !!}">
            <div class="dropdown-submenu">
                <a href="{!! url('/programs') !!}" class="text-link-sub d-flex alit-center just-between dropdown-toggle" data-toggle="dropdown">ตารางบอล โปรแกรมบอล</a>
                <ul class="dropdown-menu">
                    <li><a href="#" class="d-flex alit-center">ตารางบอล ย้อนหลัง</a></li>
                    <li><a href="#" class="d-flex alit-center">ตารางบอล ล่วงหน้า</a></li>
                </ul>
            </div>
        </a>
        <a class="dropdown-item" href="{!! url('/team') !!}">ทีม</a>
        <a class="dropdown-item" href="{!! url('/player') !!}">นักเตะ</a>
    </div>
</li>