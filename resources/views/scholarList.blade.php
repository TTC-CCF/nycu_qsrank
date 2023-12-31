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
    <title>學者名單</title>
    {{-- @vite(['resources/css/app.css', 'resources/js/app.js']) --}}
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
                    <li class="nav-item active">
                        <div class="nav-link" onclick="toList('scholar')">學者名單 <span class="sr-only">(current)</span></div>
                    </li>
                    <li class="nav-item">
                        <div class="nav-link" onclick="toList('employer')">雇主名單</div>
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
                        <div class="nav-link" onclick="export_choosing()">匯出學者資料</div>
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

        <h1>學者名單</h1>
        @if ($list->isNotEmpty())
        <div class="btn-group" role="group" id="chooseBtn">
            <button class="btn btn-outline-dark" onclick="delete_choosing()">刪除資料</button>
        </div>
        <button id="dup_btn" class="btn" onclick="showDup()"><span class="duplicate-color-block"></span>檢查重複資料</button>
        @endif
        <hr/>
        @if ($list->isNotEmpty())
        <div class="table-responsive">
            <table id='table' class="table table-striped  table-fix-column">
                <thead class="thead-dark">
                    <tr>
                        <th class="th-select" scope="col" name="select-col">選擇</th>
                        <th class="th-sn" scope="col">編號</th>
                        <th class="th-year" scope="col">年度</th>
                        <th class="th-unit" scope="col">資料提供單位</th>
                        <th class="th-unit" scope="col">資料提供者</th>
                        <th class="th-unitemail" scope="col">資料提供者Email</th>
                        <th class="th-title" scope="col">Title</th>
                        <th class="th-firstname" scope="col">First Name</th>
                        <th class="th-lastname" scope="col">Last Name</th>
                        <th class="th-chinesename" scope="col">Chinese Name</th>
                        <th class="th-position" scope="col">Job Title</th>
                        <th class="th-industry" scope="col">Department</th>
                        <th class="th-companyname" scope="col">Institution</th>
                        <th class="th-bsa" scope="col">Broad Subject Area</th>
                        <th class="th-ms" scope="col">Main Subject</th>
                        <th class="th-country" scope="col">Country</th>
                        <th class="th-email" scope="col">Email</th>
                        <th class="th-phone" scope="col">Phone</th>
                        <th class="th-senddate" scope="col">寄送Email日期</th>
                        <th class="th-currentqs" scope="col">今年是否同意參加</th>
                        <th class="th-prevqs" scope="col">去年是否同意參加</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($list as $idx => $row)
                    <tr @if ($row->is_duplicated) style="background-color:#ffbf00;" @endif>
                        <td class="td-class" name="select-col">
                            <input type="checkbox" class="big-checkbox" name="select" sn="{{ $row['SN'] }}">
                        </td>
                        <td row="index">
                            {{ $idx + 1 }}
                        </td>
                        <td row="year">
                            {{ $row['year'] }}
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
                        <td class="editable" row="資料提供者">
                            {{ $row['資料提供者'] }}
                            <button class="edit_button" onclick="editing(this, '{{ $row['SN'] }}')"><i class="fa-solid fa-pen-to-square"></i></button>
                        </td>

                        <td class="editable" row="資料提供者Email">
                            {{ $row['資料提供者Email'] }}
                            <button class="edit_button" onclick="editing(this, '{{ $row['SN'] }}')"><i class="fa-solid fa-pen-to-square"></i></button>
                        </td>
                        
                        <td class="editable" row="Title">
                            {{ $row['Title'] }}
                            <button class="edit_button" onclick='editing(this, "{{ $row["SN"] }}", @json($titles))'><i class="fa-solid fa-pen-to-square"></i></button>
                        </td>
                        <td class="editable" row="First_name">
                            {{ $row['First_name'] }}
                            <button class="edit_button" onclick="editing(this, '{{ $row['SN'] }}')"><i class="fa-solid fa-pen-to-square"></i></button>
                        </td>
                        <td class="editable" row="Last_name">
                            {{ $row['Last_name'] }}
                            <button class="edit_button" onclick="editing(this, '{{ $row['SN'] }}')"><i class="fa-solid fa-pen-to-square"></i></button>
                        </td>
                        <td class="editable" row="Chinese_name">
                            {{ $row['Chinese_name'] }}
                            <button class="edit_button" onclick="editing(this, '{{ $row['SN'] }}')"><i class="fa-solid fa-pen-to-square"></i></button>
                        </td>
                        <td class="editable" row="Job_title">
                            {{ $row['Job_title'] }}
                            <button class="edit_button" onclick="editing(this, '{{ $row['SN'] }}')"><i class="fa-solid fa-pen-to-square"></i></button>
                        </td>
                        <td class="editable" row="Department">
                            {{ $row['Department'] }}
                            <button class="edit_button" onclick="editing(this, '{{ $row['SN'] }}')"><i class="fa-solid fa-pen-to-square"></i></button>
                        </td>
                        <td class="editable" row="Institution">
                            {{ $row['Institution'] }}
                            <button class="edit_button" onclick="editing(this, '{{ $row['SN'] }}')"><i class="fa-solid fa-pen-to-square"></i></button>
                        </td>

                        <td class="editable" row="BroadSubjectArea">
                            {{ $row['BroadSubjectArea'] }}
                            <button class="edit_button" onclick="editing_bsa_ms(this, '{{ $row['SN'] }}')"><i class="fa-solid fa-pen-to-square"></i></button>
                        </td>
                        <td class="editable" row="MainSubject">
                            {{ $row['MainSubject'] }}
                            <button class="edit_button" onclick="editing_bsa_ms(this, '{{ $row['SN'] }}')"><i class="fa-solid fa-pen-to-square"></i></button>
                        </td>

                        <td class="editable" row="Country">
                            {{ $row['Country'] }}
                            <button class="edit_button" onclick='editing(this, "{{ $row["SN"] }}", @json($countries))'><i class="fa-solid fa-pen-to-square"></i></button>
                        </td>
                        <td class="editable" row="Email">
                            {{ $row['Email'] }}
                            <button class="edit_button" onclick="editing(this, '{{ $row['SN'] }}')"><i class="fa-solid fa-pen-to-square"></i></button>
                        </td>
                        <td class="editable" row="Phone">
                            {{ $row['Phone'] }}
                            <button class="edit_button" onclick="editing(this, '{{ $row['SN'] }}')"><i class="fa-solid fa-pen-to-square"></i></button>
                        </td>
                        @if ($admin)
                        <td class="editable" row="寄送Email日期">
                            {{ $row['寄送Email日期'] }}
                            <button class="edit_button" onclick="editing(this, '{{ $row['SN'] }}')"><i class="fa-solid fa-pen-to-square"></i></button>
                        </td>

                        <td class="editable" row="今年是否同意參與QS" ischeck="{{ $row['今年是否同意參與QS'] }}">
                            @if ($row['今年是否同意參與QS'])
                            <i class="fa-solid fa-check"></i>
                            @elseif ($row['今年是否同意參與QS'] === 0)
                            <i class="fa-solid fa-times"></i>
                            @endif
                            <button class="edit_button" onclick="editing(this, '{{ $row['SN'] }}')"><i class="fa-solid fa-pen-to-square"></i></button>
                        </td>
                        @else
                        <td row="寄送Email日期">
                            {{ $row['寄送Email日期'] }}
                        </td>

                        <td row="今年是否同意參與QS">
                            @if ($row['今年是否同意參與QS'])
                            <i class="fa-solid fa-check"></i>
                            @elseif ($row['今年是否同意參與QS'] === 0)
                            <i class="fa-solid fa-times"></i>
                            @endif
                        </td>
                        @endif

                        <td row="去年是否同意參與QS" ischeck="{{ $row['去年是否同意參與QS'] }}">
                            @if ($row['去年是否同意參與QS'])
                            <i class="fa-solid fa-check"></i>
                            @elseif ($row['去年是否同意參與QS'] === 0)
                            <i class="fa-solid fa-times"></i>
                            @endif
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
                    <button type="button" class="btn btn-primary" onclick="_export('scholar')">匯出</button>
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

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://unpkg.com/xlsx/dist/xlsx.core.min.js"></script>
    <script src="js/app.js"></script>
    <script src="js/addStatus.js"></script>

</body>

</html>
