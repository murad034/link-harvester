@extends('layouts.app')

@section('title', 'URLs List')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3>URLs List</h3>
        </div>
        <div class="card-body">
            <div x-data="urlTable()">
                <div class="d-flex justify-content-between mb-3">
                    <!-- for Search Box -->
                    <div class="d-flex align-items-center">
                        <label class="mb-0 me-2">Search:</label>
                        <input type="text" id="search" x-model="search" @keyup="getResults(1)" class="form-control" placeholder="Search URLs..." />
                    </div>

                    <!-- Limit Dropdown -->
                    <div class="d-flex align-items-center">
                        <select id="limit" x-model="limit" @change="getResults(1)" class="form-control">
                            <option value="10">10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                        </select> &nbsp;
                        <label class="mb-0 me-2">records</label>
                    </div>
                </div>

                <!-- Table -->
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th @click="sortTable('full_url')">URL <span :class="sortDir === 'asc' ? 'fa fa-arrow-up' : 'fa fa-arrow-down'"></span></th>
                            <th @click="sortTable('created_at')">Date Created <span :class="sortDir === 'asc' ? 'fa fa-arrow-up' : 'fa fa-arrow-down'"></span></th>
                        </tr>
                    </thead>
                    <tbody>
                    <template x-for="url in urls" :key="url.id">
                        <tr>
                            <td x-text="url.full_url"></td>
                            <td x-text="url.created_at"></td>
                        </tr>
                    </template>
                    </tbody>
                </table>

                <!-- Pagination Links -->
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <!-- Show records range -->
                    <div>
                        <span x-text="getRangeMessage()"></span>
                    </div>

                    <!-- Pagination Controls -->
                    <div>
                        <button :disabled="page <= 1" @click="getResults(page - 1)" class="pg-pad">Previous</button>

                        <template x-for="pg in visiblePages" :key="pg">
                            <button :disabled="page === pg" @click="getResults(pg)" x-text="pg" class="mx-1 pg-pad"></button>
                        </template>

                        <button :disabled="page >= lastPage" @click="getResults(page + 1)" class="pg-pad">Next</button>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <script>
        function urlTable() {
            return {
                urls: [],
                search: '',
                limit: 10,
                page: 1,
                lastPage: 1,
                totalItems: 0,
                sortBy: 'created_at',
                sortDir: 'desc',
                maxVisiblePages: 5,

                getResults(page) {
                    this.page = page || 1;

                    axios.get('/url/list', {
                        params: {
                            search: this.search,
                            limit: this.limit,
                            sort_by: this.sortBy,
                            sort_dir: this.sortDir,
                            page: this.page,
                        }
                    }).then(response => {
                        this.urls = response.data.data;
                        this.lastPage = response.data.last_page;
                        this.totalItems = response.data.total;
                    }).catch(error => {
                        console.error("There was an error fetching the data: ", error);
                    });
                },

                sortTable(column) {
                    this.sortDir = this.sortBy === column && this.sortDir === 'asc' ? 'desc' : 'asc';
                    this.sortBy = column;
                    this.getResults(1);
                },

                init() {
                    this.getResults(1);
                },

                getStartItem() {
                    return (this.page - 1) * this.limit + 1;
                },

                getEndItem() {
                    return Math.min(this.page * this.limit, this.totalItems);
                },

                getRangeMessage() {
                    return `Showing ${this.getStartItem()} to ${this.getEndItem()} of ${this.totalItems} records`;
                },

                get visiblePages() {
                    const pages = [];
                    let start = Math.max(1, this.page - Math.floor(this.maxVisiblePages / 2));
                    let end = Math.min(this.lastPage, start + this.maxVisiblePages - 1);

                    if (end - start + 1 < this.maxVisiblePages) {
                        start = Math.max(1, end - this.maxVisiblePages + 1);
                    }

                    for (let i = start; i <= end; i++) {
                        pages.push(i);
                    }
                    return pages;
                }
            };
        }

    </script>
@endsection
