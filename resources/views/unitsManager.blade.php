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
    <title>管理單位</title>
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
                    <li class="nav-item">
                        <div class="nav-link" onclick="toList('scholar')">學者名單</span></div>
                    </li>
                    <li class="nav-item">
                        <div class="nav-link" onclick="toList('employer')">雇主名單</div>
                    </li>

                    @if ($admin)
                    <li class="nav-item active">
                        <div class="nav-link" onclick="toUnitManager()">管理單位</div>
                    </li>    
                    @endif
                    
                </ul>
                <button type="button" class="btn btn-outline-dark ml-auto" onclick="logout()">登出</button>

            </div>
        </nav> 

        <h1>管理單位</h1>
        <hr>
        <div class="container-fluid">
            <div class="row">
                <div class="col-2 d-flex flex-column align-items-center">
                    <button class="btn btn-danger" data-toggle="modal" data-target="#permissionModal" >切換系所權限</button>
                </div>
                <div class="col-10">
                    <div class="container-fluid add-container">
                        <div class="row">
                            
                            <div class="col-3">
                                <div class="table-responsive">
                                    <table id='table' class="table table-striped  table-fix-column">
                                        <tbody>
                                            @foreach ($academy_list as $academy)
                                            <tr class="unit-row">
                                                <td  onclick='show_unit(@json($academy))'>{{ $academy }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table> 
                                </div>
                                
                            </div>
                            <div class="col-9 account-container">
                                <div id="accountContainer"><h3>請選擇單位<h3></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        
        
    </div>
        
    <div class="modal fade" id="permissionModal" tabindex="-1" role="dialog" aria-labelledby="permissionModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="permissionModalLabel">切換系所權限</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="{{ route('permission') }}" id="permission-form">
                        @csrf
                        <table id='table' class="table table-striped  table-fix-column">
                            <tbody>
                                <tr class="table-secondary">
                                    <td style="font-weight: bold; width: 45%;">所有系所</td>
                                    <td>
                                        <button type="button" class="btn btn-info" for="all-permit-write" onclick="changeAll('write')">檢視\修改資料</button>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-info" for="all-permit-readonly" onclick="changeAll('readonly')">檢視資料</button>
                                    </td>
                                </tr>
                                @foreach ($academy_list as $unitno => $academyName)
                                <tr class="unit-row">
                                    <td style="font-weight: bold; width: 45%;">{{ $academyName }}</td>
                                    <td class="permit-write">
                                        <label for="{{$unitno}}-permit-write">檢視\修改資料</label>
                                        <input id="{{$unitno}}-permit-write" type="radio" name="{{$unitno}}-permit" 
                                        @if ($permit_each_unit[$unitno] == 'write')
                                        checked                                            
                                        @endif
                                        value="write"/>
                                    </td>
                                    <td class="permit-readonly">
                                        <label for="{{$unitno}}-permit-readonly">檢視資料</label>
                                        <input id="{{$unitno}}-permit-readonly" type="radio" name="{{$unitno}}-permit" 
                                        @if ($permit_each_unit[$unitno] == 'readonly')
                                        checked                                            
                                        @endif
                                        value="readonly"/>
                                    </td>
                                </tr>
                                
                                @endforeach
                            </tbody>
                        </table>
                    </form>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" id="permissionModal-close">取消</button>
                    <button type="submit" class="btn btn-danger" onclick="postPermissions()">確定</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="successModal" tabindex="-1" role="dialog" aria-labelledby="successModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="alert alert-success">
                <div class="modal-body">
                    <h4 id="success-msg"><i class="fas fa-check m-1"></i></h4>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="failedModal" tabindex="-1" role="dialog" aria-labelledby="failedModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="alert alert-danger">
                <div class="modal-body">
                    <h4 id="failed-msg"><i class="fas fa-times m-1"></i></h4>
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
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" id="deleteModal-close">取消</button>
                    <button type="button" class="btn btn-danger" onclick="deleteAccountPost()">刪除</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addModalLabel"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input id="newAccount" type="text" class="form-control"  required>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" id="addModal-close">取消</button>
                    <button type="button" class="btn btn-primary" id="changeAccountBtn">新增</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">確認要修改嗎?</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" id="editModal-close">取消</button>
                    <button type="button" class="btn btn-primary" onclick="editAccountPost()">確認</button>
                </div>
            </div>
        </div>
    </div>
    
    <button id="editModal-btn" data-toggle="modal" data-target="#editModal" hidden></button>
    <button id="deleteModal-btn" data-toggle="modal" data-target="#deleteModal" hidden></button>
    <button id="addModal-btn" data-toggle="modal" data-target="#addModal" hidden></button>
    <button id="errorModal-btn" data-toggle="modal" data-target="#errorModal" hidden></button>
    <button id="successModal-btn" data-toggle="modal" data-target="#successModal" hidden></button>
    <button id="failedModal-btn" data-toggle="modal" data-target="#failedModal" hidden></button>
    <input id="units" value='@json($units)' hidden>


    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://unpkg.com/xlsx/dist/xlsx.core.min.js"></script>
    <script src="js/app.js"></script>
    <script src="js/unitsManager.js"></script>

</body>

</html>
