@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/admin.css') }}">
@endsection

@section('header_actions')
<form action="{{ route('logout') }}" method="POST">
    @csrf
    <button type="submit" class="logout-btn">logout</button>
</form>
@endsection

@section('content')
<div class="container">
    <h1 class="title">Admin</h1>

    <form class="controls" action="{{ route('admin.index') }}" method="GET">
        <div class="controls-row">
            <input type="text" class="form-control" name="keyword" value="{{ request('keyword') }}" placeholder="名前やメールアドレスを入力してください">
            <select class="form-control" name="gender">
                <option value="">性別</option>
                <option value="1" {{ request('gender')=='1'?'selected':'' }}>男性</option>
                <option value="2" {{ request('gender')=='2'?'selected':'' }}>女性</option>
                <option value="3" {{ request('gender')=='3'?'selected':'' }}>その他</option>
            </select>
            <select class="form-control" name="category">
                <option value="">お問い合わせの種類</option>
                <option value="1.商品のお届けについて" {{ request('category')=='1.商品のお届けについて'?'selected':'' }}>1.商品のお届けについて</option>
                <option value="2.商品の交換について" {{ request('category')=='2.商品の交換について'?'selected':'' }}>2.商品の交換について</option>
                <option value="3.商品トラブル" {{ request('category')=='3.商品トラブル'?'selected':'' }}>3.商品トラブル</option>
                <option value="4.ショップへのお問い合わせ" {{ request('category')=='4.ショップへのお問い合わせ'?'selected':'' }}>4.ショップへのお問い合わせ</option>
                <option value="5.その他" {{ request('category')=='5.その他'?'selected':'' }}>5.その他</option>
            </select>
            <input type="date" class="form-control" name="date" value="{{ request('date') }}">
            <button class="btn btn-search" type="submit">検索</button>
            <a class="btn btn-reset" href="{{ route('admin.index') }}">リセット</a>
        </div>
    </form>

    <div class="controls-export">
        <a class="btn btn-export" href="{{ route('admin.export', request()->query()) }}">エクスポート</a>
    </div>

    <div class="pagination">
        {{ $contacts->appends(request()->query())->links() }}
    </div>

    <div class="table-container">
        <table class="table">
            <thead class="table-header">
                <tr>
                    <th>お名前</th>
                    <th>性別</th>
                    <th>メールアドレス</th>
                    <th>お問い合わせの種類</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($contacts as $c)
                <tr>
                    <td>{{ $c->name }}</td>
                    <td>
                        @if($c->gender==1) 男性
                        @elseif($c->gender==2) 女性
                        @else その他
                        @endif
                    </td>
                    <td>{{ $c->email }}</td>
                    <td>{{ $c->category }}</td>
                    <td>
                        <button type="button"
                            class="btn btn-detail"
                            data-id="{{ $c->id }}"
                            data-name="{{ $c->name }}"
                            data-gender="{{ $c->gender }}"
                            data-email="{{ $c->email }}"
                            data-tel="{{ $c->tel }}"
                            data-address="{{ $c->address }}"
                            data-building="{{ $c->building }}"
                            data-category="{{ $c->category }}"
                            data-content="{{ $c->content }}">詳細</button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

{{-- モーダル --}}
<div class="modal" id="detailModal">
    <div class="modal__content">
        <span class="modal__close">&times;</span>
        <p><strong>お名前</strong> <span id="modal-name"></span></p>
        <p><strong>性別</strong> <span id="modal-gender"></span></p>
        <p><strong>メールアドレス</strong> <span id="modal-email"></span></p>
        <p><strong>電話番号</strong> <span id="modal-tel"></span></p>
        <p><strong>住所</strong> <span id="modal-address"></span></p>
        <p><strong>建物名</strong> <span id="modal-building"></span></p>
        <p><strong>お問い合わせの種類</strong> <span id="modal-category"></span></p>
        <p><strong>お問い合わせ内容</strong> <span id="modal-content"></span></p>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // シンプルに data-* から詰める（API不要）
    document.querySelectorAll('.btn-detail').forEach(btn => {
        btn.addEventListener('click', () => {
            const g = btn.dataset.gender === '1' ? '男性' : (btn.dataset.gender === '2' ? '女性' : 'その他');
            document.getElementById('modal-name').textContent = btn.dataset.name ?? '';
            document.getElementById('modal-gender').textContent = g;
            document.getElementById('modal-email').textContent = btn.dataset.email ?? '';
            document.getElementById('modal-tel').textContent = btn.dataset.tel ?? '';
            document.getElementById('modal-address').textContent = btn.dataset.address ?? '';
            document.getElementById('modal-building').textContent = btn.dataset.building ?? '';
            document.getElementById('modal-category').textContent = btn.dataset.category ?? '';
            document.getElementById('modal-content').textContent = btn.dataset.content ?? '';

            document.getElementById('detailModal').classList.add('active');
        });
    });

    const modal = document.getElementById('detailModal');
    modal.querySelector('.modal__close').addEventListener('click', () => modal.classList.remove('active'));
    modal.addEventListener('click', (e) => {
        if (e.target === modal) modal.classList.remove('active');
    });
</script>
@endsection