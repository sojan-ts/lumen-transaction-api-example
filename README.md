### Event Enrollment API
This is a Laravel-based API that provides endpoints for users to enroll in events. The API uses two controller methods, enroll and enrolltwo, to handle enrollment requests.

##### **Controller Methods**
**enroll**
This method uses the Illuminate\Support\Facades\DB and Illuminate\Support\Facades\Validator libraries to handle enrollment requests. The method first validates the request data using Laravel's validation rules. If the validation fails, the method returns a JSON response with an error message.

If the validation succeeds, the method begins a database transaction using **DB::beginTransaction()**. The method then tries to retrieve the user and event information from the database using** Laravel's query builder**.

If the user exists, the method retrieves the user information using a query builder. If the user does not exist, the method throws an exception.

The method then retrieves the event information using a query builder. The query builder uses a **sharedLock()** method to lock the event row to prevent other users from enrolling in the event while the transaction is in progress. The method checks if the event exists and is not full by counting the number of enrollments for the event.

If the event is full, the method throws an exception. If the event is not full, the method inserts a new enrollment into the database using a query builder. The method then commits the transaction using** DB::commit()**.

If the transaction is successful, the method returns a JSON response with a success message. If the transaction fails, the method rolls back the transaction using** DB::rollback()** and returns a JSON response with an error message.

**enrolltwo**
This method uses the Eloquent ORM to handle enrollment requests. The method first validates the request data using Laravel's validation rules. If the validation fails, the method returns a JSON response with an error message.

If the validation succeeds, the method begins a database transaction using **DB::beginTransaction()**. The method then tries to retrieve the user and event information from the database using** Eloquent models**.

If the user exists, the method retrieves the user information using the User::find() method. If the user does not exist, the method throws an exception.

The method then retrieves the event information using the Event::where() method. The method uses the **lockForUpdate()** method to lock the event row to prevent other users from enrolling in the event while the transaction is in progress. The method checks if the event exists and is not full by counting the number of enrollments for the event.

If the event is full, the method throws an exception. If the event is not full, the method creates a new Enrollment object and assigns the necessary fields. The method then saves the object to the database using the save() method.

If the transaction is successful, the method returns a JSON response with a success message. If the transaction fails, the method rolls back the transaction using **DB::rollback()** and returns a JSON response with an error message.

**Models**
The API uses three models: User, Event, and Enrollment. The User and Event models extend Laravel's Illuminate\Database\Eloquent\Model class. The Enrollment model extends Laravel's Illuminate\Database\Eloquent\Relations\Pivot class.

The User model has a one-to-many relationship with the Enrollment model. The Event model also has a one-to-many relationship with the Enrollment model.