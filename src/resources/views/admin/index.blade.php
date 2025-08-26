@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/admin.css') }}" />
@endsection

@section('content')
<div class="admin-form__content">
    <div class="admin-form__inner">
        <div class="admin-form__heading">
            <h2>Admin</h2>
        </div>
    </div>
    <form class="search-form" action="{{ route('admin.index') }}" method="GET">
        <input type="text" name="keyword" value="{{ request('keyword') }}" placeholder="名前やメールアドレスを入力してください">
        <select name="gender">
            <option value="">性別</option>
            <option value="1" {{ request('gender')=='1' ? 'selected' : '' }}>男性</option>
            <option value="2" {{ request('gender')=='2' ? 'selected' : '' }}>女性</option>
            <option value="3" {{ request('gender')=='3' ? 'selected' : '' }}>その他</option>
        </select>
        <select name="category">
            <option value="">お問い合わせの種類</option>
            <option value="商品のお届けについて">商品のお届けについて</option>
            <option value="商品の交換について">商品の交換について</option>
            <option value="商品トラブル">商品トラブル</option>
            <option value="ショップへのお問い合わせ">ショップへのお問い合わせ</option>
            <option value="その他">その他</option>
        </select>
        <select name="date">
            <option value="">年/月/日</option>
            <input type="date" name="date" value="{{ request('date') }}">
        </select>
        <div class="search-form__button">
            <button class="search-form__button-submit" type="submit">検索</button>
            <a href="{{ route('admin.index') }}"><button type="button">リセット</button></a>
        </div>
    </form>
    <div class="admin-form__export">
        <a href="{{ route('admin.export') }}">
            <button type="button">エクスポート</button>
        </a>
    </div>
    <div class="admin-table">
        <table class="admin-table__inner">
            <tr class="admin-table__row">
                <th class="admin-table__header">お名前</th>
                <th class="admin-table__header">性別</th>
                <th class="admin-table__header">メールアドレス</th>
                <th class="admin-table__header">お問い合わせの種類</th>
                <th class="admin-table__header">詳細</th>
            </tr>
            @foreach($contacts as $contact)
            <tr class="admin-table__content">
                <td class="admin-table__content">{{ $contact->name }}</td>
                <td class="admin-table__content">
                    @if($contact->gender == 1) 男性
                    @elseif($contact->gender == 2) 女性
                    @else その他
                    @endif
                </td>
                <td class="admin-table__content">{{ $contact->email }}</td>
                <td class="admin-table__content">{{ $contact->category }}</td>
                <td class="admin-table__button">
                    <button type="button" class="detail-btn" 
                    data-name="{{ $contact->name }}"
                    data-gender="{{ $contact->gender }}"
                    data-email="{{ $contact->email }}"
                    data-tel="{{ $contact->tel }}"
                    data-address="{{ $contact->address }}"
                    data-building="{{ $contact->building }}"
                    data-category="{{ $contact->category }}"
                    data-content="{{ $contact->content }}"
                    >詳細</button>
                </td>
            </tr>
            @endforeach
        </table>
        <div class="pagination">
            {{ $contacts->links() }}
        </div>
    </div>
    <div id="detailModal" class="modal hidden">
        <div class="modal-content">
            <span class="close">&times;</span>
            <p><strong>お名前</strong><span id="modal-name"></span></p>
            <p><strong>性別</strong><span id="modal-gender"></span></p>
            <p><strong>メールアドレス</strong><span id="modal-email"></span></p>
            <p><strong>電話番号</strong><span id="modal-tel"></span></p>
            <p><strong>住所</strong><span id="modal-address"></span></p>
            <p><strong>建物名</strong><span id="modal-building"></span></p>
            <p><strong>お問い合わせの種類</strong><span id="modal-category"></span></p>
            <p><strong>お問い合わせ内容</strong><span id="modal-content"></span></p>
        </div>
        <div class="admin-delete__button">
            <button class="admin-delete__button-submit" type="submit">削除</button>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById("detailModal");
        const closeBtn = modal.querySelector(".close");

        document.querySelectorAll(".detail-btn").forEach(btn => {
            btn.addEventListener("click", function() {
                let id = this.dataset.id;

                fetch('/admin/contacts/${id}')
                    .then(respanse => response.json())
                    .then(data => {
                        document.getElementById("modal-name").textContent = data.name;
                        document.getElementById("modal-gender").textContent = data.gender == 1 ? "男性" : (data.gender == 2 ? "女性" : "その他");
                        document.getElementById("modal-email").textContent = data.email;
                        document.getElementById("modal-tel").textContent = data.tel;
                        document.getElementById("modal-address").textContent = data.address;
                        document.getElementById("modal-building").textContent = data.building;
                        document.getElementById("modal-category").textContent = data.category;
                        document.getElementById("modal-content").textContent = data.content;
                        document.getElementById("modal-created").textContent = Date(data.created_at).toLocaleDateString();

                        modal.style.display = "block";
                    });
            });
        });

        closeBtn.onclick = function() {
            modal.style.display = "none";
        }

        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
    });
    </script>
    @endsection
