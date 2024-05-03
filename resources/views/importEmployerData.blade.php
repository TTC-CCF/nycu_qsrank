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
        <title>匯入雇主資料</title>
        {{-- @vite(['resources/css/app.css', 'resources/js/app.js']) --}}
        <link rel="stylesheet" href="/css/app.css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    </head>

    <body>

        <div class="list-container">

            <nav class="navbar navbar-expand-lg navbar-light bg-light">
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
                    aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <div class="nav-link" onclick="toList('scholar')">學者名單</div>
                        </li>
                        <li class="nav-item">
                            <div class="nav-link" onclick="toList('employer')">雇主名單 <span
                                    class="sr-only">(current)</span></div>
                        </li>

                        <li class="nav-item active dropdown">
                            <div class="nav-link dropdown-toggle" id="addDropdown" role="button" data-toggle="dropdown"
                                aria-haspopup="true" aria-expanded="false">
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

            <h1 id="mode" v="employer">匯入雇主資料</h1>
            <hr />
            <div id="afterDropArea"></div>
            <div id="dropArea">
                <p class="text-center">點擊或拖曳上傳檔案</p>
                <input type="file" id="fileInput" style="display: none;">
            </div>
            <div id="previewArea">
                <div class="add-container">
                    <h3>檔案預覽</h3>
                    <div class="table-responsive">
                        <table class="table table-striped  table-fix-column"></table>

                    </div>
                </div>
            </div>


        </div>

        <div class="modal fade" id="importModal" tabindex="-1" role="dialog" aria-labelledby="importModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="importModalLabel">匯入模式</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        @if (intval(session('id')) === 0)
                            <p>將更新或增加上傳名單內雇主資料，包括每年度同意回復QS結果</p>
                        @else
                            <p>將更新或增加{{ $unit_name }}雇主資料</p>
                        @endif
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">取消</button>
                        <button type="button" class="btn btn-primary" onclick="_import()">匯入</button>
                    </div>
                </div>
            </div>
        </div>

        <input id="academy_list" value='@json($academy_list)' hidden>
        <input id="bsa_list" value='@json($bsa_list)' hidden>
        <input id="ms_dict" value='@json($ms_dict)' hidden>

        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
        <script src="https://unpkg.com/xlsx/dist/xlsx.core.min.js"></script>
        <script src="/js/app.js"></script>
        <script src="/js/dragfile.js"></script>

    </body>

</html>
