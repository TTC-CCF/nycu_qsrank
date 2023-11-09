<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="css/app.css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
        <title>陽明交大QS雇主學者資料管理系統</title>
    </head>

    <body>

        <div class="login-container">
            <h1>陽明交大<br>QS雇主學者資料管理系統</h1>
        
            <form method="POST" action="{{ route('login') }}" id="loginForm">
                @csrf
                <div class="form-group">
                    <label for="unitname">單位</label>
                    <select id="unitname" name="unitname" class="form-control" form="loginForm">
                        @foreach ($units as $unit)
                        @if ($unit == 'Cirda')
                        <option value="{{ $unit }}">陽明交大大數據中心</option>
                        @else
                        <option value="{{ $unit }}">{{ $unit }}</option>
                        @endif
                        @endforeach
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="text" class="form-control" id="email" name="email" placeholder="Enter your email" required>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Enter your password" required>
                </div>
                
        
                
        
                <input type="submit" value="Login">
            </form>
            @if (session('error'))
                <p>{{ session('error') }}</p>
            @endif
        </div>
        
    
    </body>
</html>