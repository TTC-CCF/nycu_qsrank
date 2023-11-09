<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="_token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>新增雇主</title>
    <script src="https://kit.fontawesome.com/785d0df53a.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
    <link rel="stylesheet" href="css/app.css">
</head>

<body>

    <div class="list-container">
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <div class="nav-link" onclick="toList('scholar')">學者名單</div>
                    </li>
                    <li class="nav-item">
                        <div class="nav-link" onclick="toList('employer')">雇主名單</div>
                    </li>

                    <li class="nav-item active dropdown">
                        <div class="nav-link dropdown-toggle" id="addDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            新增/匯入
                        </div>
                        <div class="dropdown-menu dropdown-menu w-auto shadow p-0" aria-labelledby="addDropdown">
                            <div class="dropdown-item" onclick="AddData('scholar')">新增學者資料</div>
                            <div class="dropdown-item" onclick="AddData('employer')">新增雇主資料</div>
                            <div class="dropdown-item" onclick="ImportData('scholar')">匯入學者資料</div>
                            <div class="dropdown-item" onclick="ImportData('employer')">匯入雇主資料</div>
                        </div>
                    </li>
                    
                </ul>
                <button type="button" class="btn btn-outline-dark ml-auto" onclick="logout()">登出</button>

            </div>
        </nav> 

        <h1>新增雇主</h1>
        <hr/>
        <div class="add-container">
            <form method="post" action="/adding" id="sendForm">
                @csrf
                <div class="form-row">
                    @if ($unit == 'Admin')
                    <div class="form-group col-md-6">
                        <label for="SN">雇主編號 </label>
                        <input type="text" class="form-control" id="SN" name="SN" value="{{ $maxsn }}" readonly>
                    </div>
                    @else
                    <input type="text" class="form-control" id="SN" name="SN" value="{{ $maxsn }}" hidden>
                    @endif
                    <div class="form-group col-md-6">
                        <label for="year">年度</label>
                        <input type="text" class="form-control" id="year" name="year" value="{{ date('Y') }}" readonly>
                    </div>
                </div>

                <div class="form-row">
                    @if ($unit !== 'Admin')
                    <div class="form-group col-md-4">
                        <label for="unit">資料提供單位</label>
                        <input class="form-control" type="text" id='unit' name="UnitName" value="{{ $unit }}" readonly>
                    </div>
                    
                    @else
                    <div class="form-group col-md-4">
                        <label for="unit">資料提供單位 <i class="fa fa-asterisk" aria-hidden="true"></i></label>
                        <select id="unit" class="form-control" name="UnitName" form="sendForm" required>
                            <option hidden value>請選擇單位</option>
                            @foreach ($academy_list as $ac)
                            <option value="{{ $ac }}">{{ $ac }}</option>
                            @endforeach
                        </select>
                    </div>
                    @endif
                    <div class="form-group col-md-4">
                        <label for="provider">資料提供者 (選填)</label>
                        <input class="form-control" type="text" id='provider' name="Provider" value="{{ $unit }}">
                    </div>
                    <div class="form-group col-md-4">
                        <label for="unitEmail">資料提供者Email (選填)<i class="fa fa-asterisk" aria-hidden="true"></i></label>
                        <input class="form-control" type="text" id='unitEmail' name="UnitEmail" value="{{ $unitemail }}">
                    </div>
                </div>

                <div class="form-group">
                <label for="Title">Title <i class="fa fa-asterisk" aria-hidden="true"></i></label>
                {{-- <input type="text" class="form-control" id="Title" name="Title"> --}}
                <select name="Title" id="Title" class="form-control" form="sendForm" required>
                    <option hidden value>請選擇</option>
                    @foreach ($titles as $title)
                    <option value="{{ $title }}">{{ $title }}</option>
                    @endforeach
                </select>
                </div>
                
                <div class="form-row">
                    <div class="form-group col-md-4">
                    <label for="FirstName">First Name <i class="fa fa-asterisk" aria-hidden="true"></i></label>
                    <input type="text" class="form-control" id="FirstName" name="FirstName" required>
                    </div>
                    <div class="form-group col-md-4">
                    <label for="LastName">Last Name <i class="fa fa-asterisk" aria-hidden="true"></i></label>
                    <input type="text" class="form-control" id="LastName" name="LastName" required>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="LastName">Chinese Name (選填)</label>
                        <input type="text" class="form-control" id="ChineseName" name="ChineseName">
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group col-md-4">
                    <label for="Position">Position <i class="fa fa-asterisk" aria-hidden="true"></i></label>
                    <input type="text" class="form-control" id="Position" name="Position" required>
                    </div>
                    <div class="form-group col-md-4">
                    <label for="Industry">Industry <i class="fa fa-asterisk" aria-hidden="true"></i></label> 
                    <input type="text" class="form-control" id="Industry" name="Industry" required>
                    {{-- <select name="Industry" id="Industry" class="form-control" form="sendForm" required>
                        <option hidden value>請選擇</option>
                        @foreach ($industries as $industry)
                        <option value="{{ $industry }}">{{ $industry }}</option>
                        @endforeach
                    </select>                     --}}
                    </div>
                    <div class="form-group col-md-4">
                    <label for="CompanyName">Company Name (選填)</label>
                    <input type="text" class="form-control" id="CompanyName" name="CompanyName">
                    </div>
                </div>

                <div class="form-row">

                    <div class="form-group col-md-6">
                    <label for="BroadSubjectArea">Broad Subject Area <i class="fa fa-asterisk" aria-hidden="true"></i></label>
                    <select id="BraodSubjectArea" class="form-control" name="BroadSubjectArea" form="sendForm" required>
                        <option hidden value>請選擇</option>
                        @foreach ($bsa_list as $bsa)
                        <option value="{{ $bsa }}">{{ $bsa }}</option>
                        @endforeach
                    </select>
                    </div>
                    <div class="form-group col-md-6">
                    <label for="MainSubject">Main Subject <i class="fa fa-asterisk" aria-hidden="true"></i></label>
                    <select id="MainSubject" class="form-control" name="MainSubject" form="sendForm" required>
                        <option hidden value>-----</option>
                    </select>
                    </div>
                </div>

                <div class="form-group">
                <label for="Location">Location <i class="fa fa-asterisk" aria-hidden="true"></i></label>
                <select name="Location" id="Location" class="form-control" form="sendForm" required>
                    <option hidden value>請選擇</option>
                    @foreach ($countries as $country)
                    <option value="{{ $country }}">{{ $country }}</option>
                    @endforeach
                </select>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-6">
                    <label for="Email">Email <i class="fa fa-asterisk" aria-hidden="true"></i></label>
                    <input type="text" class="form-control" id="Email" name="Email" required>
                    </div>
                    <div class="form-group col-md-6">
                    <label for="Phone">Phone (選填)</label>
                    <input type="text" class="form-control" id="Phone" name="Phone">
                    </div>
                </div>
                @if ($unit == 'Admin')
                <div class="form-group">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="今年是否同意參與QS" name="今年是否同意參與QS">
                        <label class="form-check-label" for="今年是否同意參與QS">
                        今年是否同意參與QS
                        </label>
                    </div>
                </div>
                @endif
                
                <button type="submit" class="btn btn-primary">確定新增</button>
            </form>
        </div>
    </div>

    <input id="ms_dict" value='@json($ms_dict)' hidden>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="js/app.js"></script>

</body>

</html>