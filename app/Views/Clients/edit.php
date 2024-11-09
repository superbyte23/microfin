 
        <h2>Create Client</h2>
        <form action="<?php echo PUBLIC_URL ?>clients/<?php echo isset($client) ? htmlspecialchars($client->id) : ''; ?>" method="POST" enctype="multipart/form-data">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="first_name">First Name</label>
                        <input type="text" class="form-control" id="first_name" name="first_name" value="<?php echo isset($client) ? htmlspecialchars($client->first_name) : ''; ?>" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="last_name">Last Name</label>
                        <input type="text" class="form-control" id="last_name" name="last_name" value="<?php echo isset($client) ? htmlspecialchars($client->last_name) : ''; ?>" required>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" id="email" name="email" value="<?php echo isset($client) ? htmlspecialchars($client->email) : ''; ?>" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="phone_number">Phone Number</label>
                        <input type="text" class="form-control" id="phone_number" name="phone_number" value="<?php echo isset($client) ? htmlspecialchars($client->phone_number) : ''; ?>" required>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="address">Address</label>
                <input type="text" class="form-control" id="address" name="address" value="<?php echo isset($client) ? htmlspecialchars($client->address) : ''; ?>" required>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="date_of_birth">Date of Birth</label>
                        <input type="date" class="form-control" id="date_of_birth" name="date_of_birth" value="<?php echo isset($client) ? htmlspecialchars($client->date_of_birth) : ''; ?>" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="gender">Gender</label>
                        <select class="form-control" id="gender" name="gender" required>
                            <option value="Male" <?php echo (isset($client) && $client->gender === 'Male') ? 'selected' : ''; ?>>Male</option>
                            <option value="Female" <?php echo (isset($client) && $client->gender === 'Female') ? 'selected' : ''; ?>>Female</option>
                            <option value="Other" <?php echo (isset($client) && $client->gender === 'Other') ? 'selected' : ''; ?>>Other</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="id_number">ID Number</label>
                        <input type="text" class="form-control" id="id_number" name="id_number" value="<?php echo isset($client) ? htmlspecialchars($client->id_number) : ''; ?>" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="employment_status">Employment Status</label>
                        <select class="form-control" id="employment_status" name="employment_status" required>
                            <option value="Employed" <?php echo (isset($client) && $client->employment_status === 'Employed') ? 'selected' : ''; ?>>Employed</option>
                            <option value="Unemployed" <?php echo (isset($client) && $client->employment_status === 'Unemployed') ? 'selected' : ''; ?>>Unemployed</option>
                            <option value="Self-Employed" <?php echo (isset($client) && $client->employment_status === 'Self-Employed') ? 'selected' : ''; ?>>Self-Employed</option>
                            <option value="Student" <?php echo (isset($client) && $client->employment_status === 'Student') ? 'selected' : ''; ?>>Student</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="employer">Employer</label>
                        <input type="text" class="form-control" id="employer" name="employer" value="<?php echo isset($client) ? htmlspecialchars($client->employer) : ''; ?>">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="monthly_income">Monthly Income</label>
                        <input type="number" class="form-control" id="monthly_income" name="monthly_income" value="<?php echo isset($client) ? htmlspecialchars($client->monthly_income) : ''; ?>" step="0.01" required>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="marital_status">Marital Status</label>
                        <select class="form-control" id="marital_status" name="marital_status" required>
                            <option value="Single" <?php echo (isset($client) && $client->marital_status === 'Single') ? 'selected' : ''; ?>>Single</option>
                            <option value="Married" <?php echo (isset($client) && $client->marital_status === 'Married') ? 'selected' : ''; ?>>Married</option>
                            <option value="Divorced" <?php echo (isset($client) && $client->marital_status === 'Divorced') ? 'selected' : ''; ?>>Divorced</option>
                            <option value="Widowed" <?php echo (isset($client) && $client->marital_status === 'Widowed') ? 'selected' : ''; ?>>Widowed</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="dependents">Dependents</label>
                        <input type="number" class="form-control" id="dependents" name="dependents" value="<?php echo isset($client) ? htmlspecialchars($client->dependents) : ''; ?>" min="0" required>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="client_status">Client Status</label>
                        <select class="form-control" id="client_status" name="client_status" required>
                            <option value="active" <?php echo (isset($client) && $client->client_status === 'active') ? 'selected' : ''; ?>>Active</option>
                            <option value="inactive" <?php echo (isset($client) && $client->client_status === 'inactive') ? 'selected' : ''; ?>>Inactive</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="profile_image">Profile Image</label>
                        <input type="file" class="form-control-file" id="profile_image" name="profile_image">
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="notes">Notes</label>
                <textarea class="form-control" id="notes" name="notes" rows="3"><?php echo isset($client) ? htmlspecialchars($client->notes) : ''; ?></textarea>
            </div>

            <a href="<?php echo PUBLIC_URL ?>clients" class="btn btn-secondary">Cancel</a>
            <button type="submit" class="btn btn-primary">Save Client</button>
        </form> 
