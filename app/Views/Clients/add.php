<h2>Create Client</h2>
<form action="./store" method="POST" enctype="multipart/form-data">
  <div class="row">
    <div class="col-lg-6">
      <div class="form-group">
        <label for="first_name">First Name</label>
        <input type="text" class="form-control" id="first_name" name="first_name" required>
      </div>
    </div>
    <div class="col-lg-6">
      <div class="form-group">
        <label for="last_name">Last Name</label>
        <input type="text" class="form-control" id="last_name" name="last_name" required>
      </div>
    </div>
    <div class="col-lg-6">
      <div class="form-group">
        <label for="email">Email</label>
        <input type="email" class="form-control" id="email" name="email" required>
      </div>
    </div>
    <div class="col-lg-6">
      <div class="form-group">
        <label for="phone_number">Phone Number</label>
        <input type="text" class="form-control" id="phone_number" name="phone_number" required>
      </div>
    </div>
    <div class="col-lg-6">
      <div class="form-group">
        <label for="address">Address</label>
        <input type="text" class="form-control" id="address" name="address" required>
      </div>
    </div>
    <div class="col-lg-6">
      <div class="form-group">
        <label for="date_of_birth">Date of Birth</label>
        <input type="date" class="form-control" id="date_of_birth" name="date_of_birth" required>
      </div>
    </div>
    <div class="col-lg-6">
      <div class="form-group">
        <label for="gender">Gender</label>
        <select class="form-control" id="gender" name="gender" required>
          <option value="Male">Male</option>
          <option value="Female">Female</option>
          <option value="Other">Other</option>
        </select>
      </div>
    </div>
    <div class="col-lg-6">
      <div class="form-group">
        <label for="id_number">ID Number</label>
        <input type="text" class="form-control" id="id_number" name="id_number" required>
      </div>
    </div>
    <div class="col-lg-6">
      <div class="form-group">
        <label for="employment_status">Employment Status</label>
        <select class="form-control" id="employment_status" name="employment_status" required>
          <option value="Employed">Employed</option>
          <option value="Unemployed">Unemployed</option>
          <option value="Self-Employed">Self-Employed</option>
          <option value="Student">Student</option>
        </select>
        
      </div>
    </div>
    <div class="col-lg-6">
      <div class="form-group">
        <label for="employer">Employer</label>
        <input type="text" class="form-control" id="employer" name="employer">
      </div>
    </div>
    <div class="col-lg-6">
      <div class="form-group">
        <label for="monthly_income">Monthly Income</label>
        <input type="number" class="form-control" id="monthly_income" name="monthly_income" step="0.01" required>
      </div>
    </div>
    <div class="col-lg-6">
      <div class="form-group">
        <label for="marital_status">Marital Status</label>
        <select class="form-control" id="marital_status" name="marital_status" required>
          <option value="Single">Single</option>
          <option value="Married">Married</option>
          <option value="Divorced">Divorced</option>
          <option value="Widowed">Widowed</option>
        </select>
      </div>
    </div>
    <div class="col-lg-6">
      <div class="form-group">
        <label for="dependents">Dependents</label>
        <input type="number" class="form-control" id="dependents" name="dependents" min="0" required>
      </div>
    </div>
    <div class="col-lg-6">
      <div class="form-group">
        <label for="client_status">Client Status</label>
        <select class="form-control" id="client_status" name="client_status" required>
          <option value="active">Active</option>
          <option value="inactive">Inactive</option>
        </select>
      </div>
    </div>
    <div class="col-lg-6">
      <div class="form-group">
        <label for="profile_image">Profile Image</label>
        <input type="file" class="form-control-file" id="profile_image" name="profile_image">
      </div>
    </div>
    <div class="col-lg-6">
      <div class="form-group">
        <label for="notes">Notes</label>
        <textarea class="form-control" id="notes" name="notes" rows="3"></textarea>
      </div>
    </div>
  </div>
  <a href="../clients" class="btn btn-secondary">Cancel</a> 
  <button type="submit" class="btn btn-primary">Create Client</button>
</form>