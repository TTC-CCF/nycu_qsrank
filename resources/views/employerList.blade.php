<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="_token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="cache-control" content="no-cache">
    <meta http-equiv="pragma" content="no-cache">
    <meta http-equiv="expires" content="0">
    <script src="https://kit.fontawesome.com/785d0df53a.js" crossorigin="anonymous"></script>
    <title>雇主名單</title>
    <link rel="stylesheet" href="css/app.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

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
                    <li class="nav-item active">
                        <div class="nav-link" onclick="toList('employer')">雇主名單 <span class="sr-only">(current)</span></div>
                    </li>

                    <li class="nav-item dropdown">
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

                    <li class="nav-item">
                        <div class="nav-link" onclick="export_choosing()">匯出雇主資料</div>
                    </li>
                    @if ($admin)
                    <li class="nav-item">
                        <div class="nav-link" onclick="toUnitManager()">管理單位</div>
                    </li>    
                    @endif
                    
                </ul>
                <button type="button" class="btn btn-outline-dark ml-auto" onclick="logout()">登出</button>

            </div>
        </nav>
        <h1 class="col">雇主名單</h1>
        <div class="dropdown">
            <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              {{ $academy_list[$unit] }}
            </button>
            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
              @foreach ($academy_list as $unitno => $academy)
                  <a class="dropdown-item" href="/?unit={{$unitno}}">{{ $academy }}</a>
              @endforeach
            </div>
        </div>
        @if ($list->isNotEmpty())
        <div class="btn-group" role="group" id="chooseBtn">
            <button class="btn btn-outline-dark" onclick="delete_choosing()">刪除</button>
        </div>
        <button id="dup_btn" class="btn" onclick="showDup()"><span class="duplicate-color-block"></span>檢查重複資料</button>

        @endif
        <hr/>
        @if ($list->isNotEmpty())
        <div class="table-responsive">
            <table id="table" class="table table-striped table-fix-column">
                <thead class="thead-dark">
                    <tr>
                        <th class="th-select" scope="col" name="select-col">選擇</th>
                        <th class="th-sn" scope="col">編號</th>
                        <th class="th-unit" scope="col">資料提供單位</th>
                        <th class="th-unit" scope="col">資料提供者</th>
                        <th class="th-unitemail" scope="col">資料提供者Email</th>
                        <th class="th-title" scope="col">Title</th>
                        <th class="th-firstname" scope="col">First Name</th>
                        <th class="th-lastname" scope="col">Last Name</th>
                        <th class="th-chinesename" scope="col">Chinese Name</th>
                        <th class="th-position" scope="col">Position</th>
                        <th class="th-industry" scope="col">Industry</th>
                        <th class="th-companyname" scope="col">Company Name</th>
                        <th class="th-bsa" scope="col">Broad Subject Area</th>
                        <th class="th-ms" scope="col">Main Subject</th>
                        <th class="th-country" scope="col">Location</th>
                        <th class="th-email" scope="col">Email</th>
                        @foreach($year_results as $year => $_)
                            <th class="th-currentqs" scope="col">{{ $year }}同意參與QS</th>
                        @endforeach
                        <th class="th-senddate" scope="col">寄送Email日期</th>
                        <th class="th-phone" scope="col">Phone</th>
                        <th class="th-dupUnits" scope="col">重複系所</th>
                        
                    </tr>
                </thead>
                <tbody>
                    @foreach ($list as $idx => $row)
                    <tr @if ($row["dupUnits"]) style="background-color:#ffbf00;" @endif>
                        <td class="td-class" name="select-col">
                            <input type="checkbox" class="big-checkbox" name="select" sn="{{ $row['SN'] }}">
                        </td>
                        <td row="index">
                            {{ $idx + 1 }}
                        </td>
                         
                        @if ($admin)
                        <td class="editable" row="資料提供單位">
                            {{ $row['資料提供單位'] }}
                            <button class="edit_button" onclick='editing(this, "{{ $row["SN"] }}", @json($academy_list))'><i class="fa-solid fa-pen-to-square"></i></button>
                        </td>
                        
                        @else
                        <td row="資料提供單位">
                            {{ $row['資料提供單位'] }}
                        </td>
                        
                        @endif
                        <td @if(session('id') === $unit || $admin) class="editable" @endif row="資料提供者">
                            {{ $row['資料提供者'] }}
                            <button class="edit_button" onclick="editing(this, '{{ $row['SN'] }}')"><i class="fa-solid fa-pen-to-square"></i></button>
                        </td>

                        <td @if(session('id') === $unit || $admin) class="editable" @endif row="資料提供者Email">
                            {{ $row['資料提供者Email'] }}
                            <button class="edit_button" onclick="editing(this, '{{ $row['SN'] }}')"><i class="fa-solid fa-pen-to-square"></i></button>
                        </td>
                        <td @if(session('id') === $unit || $admin) class="editable" @endif row="Title">
                            {{ $row['Title'] }}
                            <button class="edit_button" onclick='editing(this, "{{ $row["SN"] }}", @json($titles))'><i class="fa-solid fa-pen-to-square"></i></button>
                        </td>
                        <td @if(session('id') === $unit || $admin) class="editable" @endif row="First_name">
                            {{ $row['First_name'] }}
                            <button class="edit_button" onclick="editing(this, '{{ $row['SN'] }}')"><i class="fa-solid fa-pen-to-square"></i></button>
                        </td>
                        <td @if(session('id') === $unit || $admin) class="editable" @endif row="Last_name">
                            {{ $row['Last_name'] }}
                            <button class="edit_button" onclick="editing(this, '{{ $row['SN'] }}')"><i class="fa-solid fa-pen-to-square"></i></button>
                        </td>
                        <td @if(session('id') === $unit || $admin) class="editable" @endif row="Chinese_name">
                            {{ $row['Chinese_name'] }}
                            <button class="edit_button" onclick="editing(this, '{{ $row['SN'] }}')"><i class="fa-solid fa-pen-to-square"></i></button>
                        </td>
                        <td @if(session('id') === $unit || $admin) class="editable" @endif row="Position">
                            {{ $row['Position'] }}
                            <button class="edit_button" onclick="editing(this, '{{ $row['SN'] }}')"><i class="fa-solid fa-pen-to-square"></i></button>
                        </td>
                        <td @if(session('id') === $unit || $admin) class="editable" @endif row="Industry">
                            {{ $row['Industry'] }}
                            <button class="edit_button" onclick='editing(this, "{{ $row["SN"] }}", @json($industries))'><i class="fa-solid fa-pen-to-square"></i></button>
                        </td>
                        <td @if(session('id') === $unit || $admin) class="editable" @endif row="CompanyName">
                            {{ $row['CompanyName'] }}
                            <button class="edit_button" onclick="editing(this, '{{ $row['SN'] }}')"><i class="fa-solid fa-pen-to-square"></i></button>
                        </td>
                        <td @if(session('id') === $unit || $admin) class="editable" @endif row="BroadSubjectArea">
                            {{ $row['BroadSubjectArea'] }}
                            <button class="edit_button" onclick="editing_bsa_ms(this, '{{ $row['SN'] }}')"><i class="fa-solid fa-pen-to-square"></i></button>
                        </td>
                        <td @if(session('id') === $unit || $admin) class="editable" @endif row="MainSubject">
                            {{ $row['MainSubject'] }}
                            <button class="edit_button" onclick="editing_bsa_ms(this, '{{ $row['SN'] }}')"><i class="fa-solid fa-pen-to-square"></i></button>
                        </td>
                        <td @if(session('id') === $unit || $admin) class="editable" @endif row="Location">
                            {{ $row['Location'] }}
                            <button class="edit_button" onclick='editing(this, "{{ $row["SN"] }}", @json($countries))'><i class="fa-solid fa-pen-to-square"></i></button>
                        </td>
                        <td @if(session('id') === $unit || $admin) class="editable" @endif row="Email">
                            {{ $row['Email'] }}
                            <button class="edit_button" onclick="editing(this, '{{ $row['SN'] }}')"><i class="fa-solid fa-pen-to-square"></i></button>
                        </td>
                        @if ($admin)
                        
                            @foreach($year_results as $year => $result) 

                            <td class="editable" row="{{$year}}同意參與QS" type="checkbox" isCheck={{ $result[$row["SN"]] }}>
                                @if ($result[$row["SN"]] === 1)
                                <i class="fa-solid fa-check"></i>
                                @elseif ($result[$row["SN"]] === 0)
                                <i class="fa-solid fa-times"></i>
                                @endif
                                
                                <button class="edit_button" onclick="editing(this, '{{ $row['SN'] }}')"><i class="fa-solid fa-pen-to-square"></i></button>
                            </td>
                            @endforeach
                            <td class="editable" row="寄送Email日期" type="date">
                                {{ $row['寄送Email日期'] }}
                                <button class="edit_button" onclick="editing(this, '{{ $row['SN'] }}')"><i class="fa-solid fa-pen-to-square"></i></button>
                            </td>
                        @else
                        
                            @foreach($year_results as $year => $result) 
                                <td row="{{$year}}同意參與QS" type="checkbox">
                                    @if ($result[$row["SN"]] === 1)
                                    <i class="fa-solid fa-check"></i>
                                    @elseif ($result[$row["SN"]] === 0)
                                    <i class="fa-solid fa-times"></i>
                                    @endif
                                </td>
                            @endforeach
                            <td row="寄送Email日期">
                                {{ $row['寄送Email日期'] }}
                            </td>
                        @endif
                        <td @if(session('id') === $unit || $admin) class="editable" @endif row="Phone">
                            {{ $row['Phone'] }}
                            <button class="edit_button" onclick="editing(this, '{{ $row['SN'] }}')"><i class="fa-solid fa-pen-to-square"></i></button>
                        </td>
                        <td row="dupUnits">
                            <pre>{{ $row["dupUnits"] }}</pre>
                        </td>
                       
                    </tr>
                    @endforeach
                </tbody>
                
            </table>
            
        </div>
        @else
            <h3>沒有資料</h3>
        @endif
    </div>
    <!-- Flexbox container for aligning the toasts -->
    
    <div class="modal fade" id="addModalSuccess" tabindex="-1" role="dialog" aria-labelledby="addModalSuccessLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="alert alert-success">
                <div class="modal-body">
                    <h4><i class="fas fa-check m-1"></i>{{ $add_status }}</h4>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addModalFailed" tabindex="-1" role="dialog" aria-labelledby="addModalFailedLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="alert alert-danger">
                <div class="modal-body">
                    <h4><i class="fas fa-times m-1"></i>{{ $add_status }}</h4>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">真的要刪除嗎</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>請再次確認要刪除的資料，此操作無法復原!</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">取消</button>
                    <button type="button" class="btn btn-primary" onclick="del()">刪除</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="exportModal" tabindex="-1" role="dialog" aria-labelledby="exportModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exportModalLabel">匯出資料</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>將匯出所選取的資料行成為Excel檔案</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">取消</button>
                    <button type="button" class="btn btn-primary" onclick="_export('employer')">匯出</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="errorModal" tabindex="-1" role="dialog" aria-labelledby="errorModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="errorModalLabel">請選擇資料!</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">取消</button>
                </div>
            </div>
        </div>
    </div>
    <button id="deleteModal-btn" data-toggle="modal" data-target="#deleteModal" hidden></button>
    <button id="exportModal-btn" data-toggle="modal" data-target="#exportModal" hidden></button>
    <button id="errorModal-btn" data-toggle="modal" data-target="#errorModal" hidden></button>
    <button id="addModal-Success-btn" data-toggle="modal" data-target="#addModalSuccess" hidden></button>
    <button id="addModal-Failed-btn" data-toggle="modal" data-target="#addModalFailed" hidden></button>
    <input id="addStatus" value="{{ $add_status }}" hidden>
    
    <input id="academy_list" value='@json($academy_list)' hidden>
    <input id="bsa_list" value='@json($bsa_list)' hidden>
    <input id="ms_dict" value='@json($ms_dict)' hidden>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
    <script src="https://unpkg.com/xlsx/dist/xlsx.core.min.js"></script>
    <script src="js/app.js"></script>
    <script src="js/addStatus.js"></script>
    
</body>

</html>
