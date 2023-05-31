<?php
/*****************************************************
 * 프로젝트명   : laravel_board
 * 디렉토리     : Controllers
 * 파일명       : UserController.php
 * 이력         : v001 0530 AR.Choe new
 *****************************************************/

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\User;

class UserController extends Controller
{
    public function login() {
        return view('/login');
    }

    public function registration() {
        return view('/registration');
    }

    public function loginpost(Request $req) {
        // 유효성 체크
        $req->validate([
            'email'    => 'required|email|between:3,100'
            ,'password' => 'regex:/^(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[!@#$%^*-]).{8,20}$/'
        ]);

        // 유저 정보 습득
        $user = User::where('email', $req->email)->first();
        if (!$user || !(Hash::check($req->password, $user->password))) {
            $error = '아이디와 비밀번호를 확인해주세요.';
            return redirect()
                ->back()
                ->with('error', $error);
        }

        // 유저 인증 작업
        // ?? auth 지워도 로그인됨;
        Auth::login($user);
        if (Auth::check()) {
            session($user->only('id')); // 세션에 인증된 회원의 pk값을 등록
            return redirect()->intended(route('boards.index'));
        } else {
            $error = '유저 인증작업 에러.';
            return redirect()
                ->back()
                ->with('error', $error);
        }
    }

    public function registrationpost(Request $req) {
        // 유효성 체크
        $req->validate([
                'name'      => 'required|regex:/^[가-힣]+$/|min:2|max:30'
                ,'email'    => 'required|email|between:3,100'
                ,'password' => 'same:passwordChk|regex:/^(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[!@#$%^*-]).{8,20}$/'
            ]);

        // $data['name'] = "박".mb_substr($req->name, 1);
        $data['name'] = $req->name;
        $data['email'] = $req->email;
        $data['password'] = Hash::make($req->password); // 암호화

        // insert
        $user = User::create($data);
        if (!$user) {
            // todo 이 부분 제대로 되는지 확인, 특히 중복된 이메일 에러
            $error = '시스템 에러가 발생하여, 회원가입에 실패했습니다.';
            return redirect()
                ->route('users.registration')
                ->with('error', $error);
        }

        // 회원가입 완료 로그인 페이지로 이동
        return redirect()
            ->route('users.login')
            ->with('success', '회원가입이 완료되었습니다. 로그인해주세요.');
            // session에 저장이 됨
    }

    public function logout() {
        Session::flush(); // 세션 파기
        Auth::logout(); // 로그아웃
        return redirect()->route('users.login');
    }

    public function withdraw() {
        if(auth()->guest()) {
            return redirect()->route('users.login');
        }

        $id = session('id');
        $result = User::destroy($id);
        // todo 정상처리 되었는지 확인, 트라이캐치, 에러핸들링

        Session::flush(); // 세션 파기
        Auth::logout(); // 로그아웃

        return redirect()->route('users.login');
    }

    public function edit() {
        if(auth()->guest()) {
            return redirect()->route('users.login');
        }

        $id = session('id');

        return view('/useredit')->with('data', User::findOrFail($id));
    }

    public function editpost(Request $req) {
        $arrKey = [];

        $users = User::find(Auth::User()->id); // 세션에 pk 저장하는 것보다 보안적으로 더 좋을 수 있음

        if (Hash::check($req->password, $users->password)) {
            return redirect()->back()->with('error', '기존 비밀번호와 일치합니다.');
        }

        if($users->name !== $req->name) {
            $arrKey[] = 'name';
        }
        if(isset($req->password)) {
            $arrKey[] = 'password';
        }

        // todo 사이트에서 사용하는 모든 유효성을
        // todo 유틸리티 클래스에 따로 작성
        $chkList = [
            'name'      => 'required|regex:/^[가-힣]+$/|min:2|max:30'
            ,'password' => 'same:passwordChk|regex:/^(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[!@#$%^*-]).{8,20}$/'
        ];
        
        $arrChk = [];
        foreach ($arrKey as $val) {
            $arrChk[$val] = $chkList[$val];
        }
        // todo ***********************************

        $req->validate($arrChk);

        foreach ($arrKey as $val) {
            if ($val === 'password') {
                $users->$val = Hash::make($req->$val);
                continue;
            }
            $users->$val = $req->$val;
        }

        $users->save();
        return redirect()->route('users.edit');
    }
}
