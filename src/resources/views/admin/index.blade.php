@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/admin.css') }}">

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Gorditas:wght@400;700&family=Noto+Serif+JP:wght@200..900&family=PT+Serif:ital,wght@0,400;0,700;1,400;1,700&display=swap" rel="stylesheet">

@endsection

@section('header_actions')
<a class="header-btn" href="{{ route('logout') }}">logout</a>
@endsection

@section('content')

<div class="admin-form__content">
    <div class="admin__heading">
        <p class="admin__title">Admin</p>
    </div>
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
                <option value="1.商品のお届けについて" {{ request('category')=='1.商品のお届けについて'?'selected':'' }}>商品のお届けについて</option>
                <option value="2.商品の交換について" {{ request('category')=='2.商品の交換について'?'selected':'' }}>商品の交換について</option>
                <option value="3.商品トラブル" {{ request('category')=='3.商品トラブル'?'selected':'' }}>商品トラブル</option>
                <option value="4.ショップへのお問い合わせ" {{ request('category')=='4.ショップへのお問い合わせ'?'selected':'' }}>ショップへのお問い合わせ</option>
                <option value="5.その他" {{ request('category')=='5.その他'?'selected':'' }}>その他</option>
            </select>
            <input type="date" class="form-control" name="date" value="{{ request('date') }}">

            <div class="form-button">
                <button class="btn btn-search" type="submit">検索</button>
                <a class="btn btn-reset" href="{{ route('admin.index') }}">リセット</a>
            </div>
        </div>
    </form>
    <div class="controls-row">
        <div class="controls-export">
            <a class="btn btn-export" href="{{ route('admin.export', request()->query()) }}">エクスポート</a>
        </div>
        <div class="pagination-wrapper">
            {{ $contacts->appends(request()->query())->links('pagination') }}
        </div>
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
        <div class="modal__row"><strong>お名前</strong> <span id="modal-name"></span></div>
        <div class="modal__row"><strong>性別</strong> <span id="modal-gender"></span></div>
        <div class="modal__row"><strong>メールアドレス</strong> <span id="modal-email"></span></div>
        <div class="modal__row"><strong>電話番号</strong> <span id="modal-tel"></span></div>
        <div class="modal__row"><strong>住所</strong> <span id="modal-address"></span></div>
        <div class="modal__row"><strong>建物名</strong> <span id="modal-building"></span></div>
        <div class="modal__row"><strong>お問い合わせの種類</strong> <span id="modal-category"></span></div>
        <div class="modal__row"><strong>お問い合わせ内容</strong> <span id="modal-content"></span></div>


        <div class="modal__footer">
            <form id="deleteForm" method="POST" action="">
                @csrf
                @method('DELETE')
                <button class="btn btn-delete" type="submit">削除</button>
            </form>
        </div>
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

            const id = btn.dataset.id;
            document.getElementById('deleteForm').action = `/admin/${id}`;
            document.getElementById('detailModal').classList.add('active');
        });
    });

    const modal = document.getElementById('detailModal');
    modal.querySelector('.modal__close').addEventListener('click', () => modal.classList.remove('active'));
    modal.addEventListener('click', (e) => {
        if (e.target === modal) modal.classList.remove('active');
    });
    const deleteForm = document.getElementById('deleteForm');
    deleteForm.addEventListener('submit', (e) => {
        if (!confirm('このお問い合わせを削除します。よろしいですか？')) {
            e.preventDefault();
        }
    });
</script>
@endsection