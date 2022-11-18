@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between">
                            <div>Posts</div>
                            <div><a href="{{ route('posts.create') }}" class="btn btn-success">Create Post</a> </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="mb-2">
                            <form action="" class="form-inline">
                                <label for="category_filter">Filter By Category &nbsp;</label>
                                <select name="category" id="category_filter" class="form-control">
                                    <option value="">Select Category</option>
                                    @if (count($categories))
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->name }}"
                                                {{ Request::query('category') && Request::query('category') == $category->name ? 'selected' : '' }}>
                                                {{ $category->name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                                <label for="keyword">&nbsp;&nbsp;</label>
                                <input type="text" name="keyword" placeholder="Enter your keyword" id="keyword"
                                    class="form-control">
                                <span>&nbsp;</span>
                                <button type="button" onclick="search_post()" class="btn btn-primary">Search</button>

                                @if (Request::query('category') || Request::query('keyword'))
                                    <a href="{{ route('posts.index') }}" class="btn btn-success">Clear</a>
                                @endif

                            </form>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-stripped">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Title</th>
                                        <th>Created By</th>
                                        <th>Category</th>
                                        <th>
                                            Total Comments
                                            {{-- <a href="#"><i class="fa fa-sort-down"></i> </a>
                                            <a href="#"><i class="fa fa-sort-up"></i> </a> --}}
                                            @if (Request::query('sortByComments') && Request::query('sortByComments') == 'asc')
                                                <a href="javascript:sort('dsc')"><i class="fa fa-sort-down"></i> </a>
                                            @elseif (Request::query('sortByComments') && Request::query('sortByComments') == 'dsc')
                                                <a href="javascript:sort('asc')"><i class="fa fa-sort-up"></i> </a>
                                            @else
                                                <a href="javascript:sort('asc')"><i class="fa fa-sort"></i> </a>
                                            @endif

                                        </th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @if (count($posts))
                                        @foreach ($posts as $post)
                                            <tr>
                                                <td>{{ $post->id }}</td>
                                                <td style="width:35%">{{ $post->title }}</td>
                                                <td>{{ $post->user->name }}</td>
                                                <td>{{ $post->category->name }}</td>
                                                <td align="center">{{ $post->comments_count }}</td>
                                                <td style="width:250px;">
                                                    <a href="{{ route('posts.show', $post->id) }}"
                                                        class="btn btn-primary">View</a>
                                                    <a href="{{ route('posts.edit', $post->id) }}"
                                                        class="btn btn-success">Edit</a>
                                                    <a href="#" class="btn btn-danger">Delete</a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="6">No Posts Found</td>

                                        </tr>
                                    @endif

                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('javascript')
    <script>
        let query = <?php echo json_encode((object) Request::query()); ?>;

        function search_post() {
            Object.assign(query, {
                'category': $('#category_filter').val()
            });

            Object.assign(query, {
                'keyword': $('#keyword').val()
            });

            window.location.href = "{{ route('posts.index') }}?" + $.param(query);
        }

        function sort(value) {
            Object.assign(query, {
                'sortByComments': value
            })
            window.location.href = "{{ route('posts.index') }}?" + $.param(query);
        }
    </script>
@endsection
