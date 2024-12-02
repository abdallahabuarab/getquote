
<div class="search-container">
    <div class="d-flex justify-content-between align-items-center mb-2">
        <h4>Search</h4>
        <button id="toggleSearchBtn" class="btn">
            <i id="toggleIcon" class="fas fa-plus"></i>
        </button>
    </div>
    <div id="searchFilters" class="search-filters d-none">
        <!-- Search Filters -->
        <form action="{{ $action}}" method="GET">
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
<script>
    document.getElementById('toggleSearchBtn').addEventListener('click', function() {
        var searchFilters = document.getElementById('searchFilters');
        var toggleIcon = document.getElementById('toggleIcon');
        var isFiltersVisible = searchFilters.classList.contains('d-none');

        if (isFiltersVisible) {
            searchFilters.classList.remove('d-none');
            toggleIcon.classList.remove('fa-plus');
            toggleIcon.classList.add('fa-minus');
        } else {
            searchFilters.classList.add('d-none');
            toggleIcon.classList.remove('fa-minus');
            toggleIcon.classList.add('fa-plus');
        }
    });
</script>
<style>
    .search-container {
        background-color: white;
        padding: 15px;
        border-radius: 5px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        margin-bottom: 20px;
    }

    .search-header button {
        float: right;
        /* Aligns the button to the right */
    }

    .search-filters {
        margin-top: 15px;
    }

    .search-filters .form-row {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        /* Spacing between form items */
    }
</style>


{{-- <div class="search-container">
    <!-- Search Filters -->
    <form action="{{ $action }}" method="GET">
        <div class="form-row align-items-center mb-2">
            <div class="col-auto">
                <input type="text" name="search" class="form-control" placeholder="{{ $placeholder }}" value="{{ request('search') }}">
            </div>
            <!-- Add more inputs/selects as needed -->
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
</div> --}}
