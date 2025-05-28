<div class="search-container">
    <h4>Search</h4>

    <div id="searchFilters" class="search-filters">
        <!-- Search Filters -->
        <form action="{{ $action }}" method="GET">
            <div class="form-row align-items-center mb-2">
                <div class="col-auto">
                    <input type="text" name="search" class="form-control" placeholder="{{ $placeholder }}"
                        value="{{ request('search') }}">
                </div>
            </div>
            <div class="form-row">
                <div class="col-auto">
                    <button type="submit" class="btn btn-outline-secondary">Search</button>
                </div>
                <div class="col-auto">
                    <a href="{{ $action }}" class="btn btn-outline-danger">Reset</a>
                </div>
            </div>
        </form>
    </div>
</div>

<style>
    .search-container {
        background-color: white;
        padding: 15px;
        border-radius: 5px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        margin-bottom: 20px;
    }

    .search-filters {
        margin-top: 15px;
    }

    .search-filters .form-row {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
    }
</style>
