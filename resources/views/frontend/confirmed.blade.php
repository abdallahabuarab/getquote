<div class="container mt-5">
    <h2>Complete your info</h2>
    <form action="{{ route('finalize.interaction') }}" method="POST" >
        @csrf
        @if($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif
        <div class="form-group" >
            <label >Category Chosen</label>
            <input type="text" class="form-control" value="{{$category->code}}" style="background-color: #ccc" readonly>
        </div>
        <div class="form-group">
            <label >Base price</label>
            <input type="text" class="form-control" value="{{$category->base_price}}" style="background-color: #ccc" readonly>
        </div>
        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" class="form-control" name="customer_name" id="name" >
        </div>
        <div class="form-group">
            <label for="name">Email</label>
            <input type="text" class="form-control" name="email" id="name" >
        </div>
        <div class="form-group">
            <label for="phone">Contact Phone</label>
            <input type="tel" class="form-control" name="customer_contact" id="phone">
        </div>


        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<style>
   .container {
    max-width: 600px;
    margin: auto;
    font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
    color: #333;
    background-color: #f8f9fa;
    padding: 20px;
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    border-radius: 8px;
}

h2 {
    text-align: center;
    color: #007bff;
}
.alert-danger {
    background-color: #f8d7da;
    border-color: #f5c6cb;
    color: #721c24;
    padding: 10px 15px;
    margin-bottom: 20px;
    border: 1px solid transparent;
    border-radius: .25rem;
}

.form-group label {
    font-weight: bold;
}

input[type="text"],
input[type="email"],
input[type="tel"],
button.btn-primary {
    width: 100%;
    padding: 12px 20px;
    margin-top: 8px;
    border: 1px solid #ccc;
    border-radius: 4px;
    box-sizing: border-box; /* Include padding and border in the element's total width and height */
}

button.btn-primary {
    background-color: #0056b3;
    color: white;
    border: none;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

button.btn-primary:hover {
    background-color: #004494;
}

/* Add responsiveness to form */
@media (max-width: 768px) {
    .container {
        width: 90%;
        padding: 10px;
    }
}


</style>
