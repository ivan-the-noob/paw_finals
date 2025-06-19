<?php
require '../../../../db.php';

try {
    // Pagination settings
    $perPage = 10;
    $page = isset($_GET['page']) ? $_GET['page'] : 1;
    $offset = ($page - 1) * $perPage;

    // Total count for pagination (optional)
    $totalServicesResult = $conn->query("SELECT COUNT(*) AS total FROM service_list");
    $totalServicesRow = $totalServicesResult->fetch_assoc();
    $totalServices = $totalServicesRow['total'];
    $totalPages = ceil($totalServices / $perPage);

    // Fetch services with pagination
    $sql = "SELECT * FROM service_list LIMIT $perPage OFFSET $offset";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        throw new Exception("Prepare statement for services failed: " . $conn->error);
    }

    $stmt->execute();
    $result = $stmt->get_result();

    $services = [];
    while ($row = $result->fetch_assoc()) {
        $services[] = $row;
    }
    $stmt->close();

    if ($services) {
        $counter = 1;
        foreach ($services as $service) {
            echo "<tr>";
            echo "<td>" . $counter . "</td>";
            echo "<td>" . htmlspecialchars($service['service_type']) . "</td>";
            echo "<td>" . htmlspecialchars($service['service_name']) . "</td>";
            echo "<td>₱" . htmlspecialchars($service['cost']) . "</td>";
            echo "<td>" . htmlspecialchars($service['discount']) . "%</td>";
            echo "<td>" . htmlspecialchars($service['info']) . "</td>";
            echo '<td>
                    <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#editModal' . $counter . '"><i class="fas fa-edit"></i></button>
                    <button class="btn btn-danger btn-sm" data-toggle="modal" data-target="#deleteModal' . $counter . '"><i class="fas fa-trash-alt"></i></button>
                  </td>';
            echo "</tr>";

            // Edit Modal
            echo '
            <div class="modal fade" id="editModal' . $counter . '" tabindex="-1" role="dialog" aria-labelledby="editModalLabel' . $counter . '" aria-hidden="true">
              <div class="modal-dialog" role="document">
                <form method="POST" action="../../function/php/edit_service.php">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title">Edit Service</h5>
                      <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                      <input type="hidden" name="id" value="' . $service['id'] . '">
                      <div class="form-group">
                        <label>Service Type</label>
                        <input type="text" class="form-control" name="service_type" value="' . htmlspecialchars($service['service_type']) . '" required>
                      </div>
                      <div class="form-group">
                        <label>Service Name</label>
                        <input type="text" class="form-control" name="service_name" value="' . htmlspecialchars($service['service_name']) . '" required>
                      </div>
                      <div class="form-group">
                        <label>Cost</label>
                        <input type="number" class="form-control" name="cost" value="' . htmlspecialchars($service['cost']) . '" required>
                      </div>
                      <div class="form-group">
                        <label>Discount (%)</label>
                        <input type="number" class="form-control" name="discount" value="' . htmlspecialchars($service['discount']) . '">
                      </div>
                      <div class="form-group">
                        <label>Info</label>
                        <textarea class="form-control" name="info">' . htmlspecialchars($service['info']) . '</textarea>
                      </div>
                    </div>
                    <div class="modal-footer">
                      <button type="submit" class="btn btn-primary">Save changes</button>
                      <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    </div>
                  </div>
                </form>
              </div>
            </div>';

            // Delete Modal
            echo '
            <div class="modal fade" id="deleteModal' . $counter . '" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel' . $counter . '" aria-hidden="true">
              <div class="modal-dialog" role="document">
                <form method="GET" action="../../function/php/delete_service.php">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title">Confirm Deletion</h5>
                      <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                      Are you sure you want to delete "<strong>' . htmlspecialchars($service['service_name']) . '</strong>"?
                      <input type="hidden" name="id" value="' . $service['id'] . '">
                    </div>
                    <div class="modal-footer">
                      <button type="submit" class="btn btn-danger">Delete</button>
                      <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    </div>
                  </div>
                </form>
              </div>
            </div>';

            $counter++;
        }
    } else {
        echo "<tr><td colspan='7'>No services found.</td></tr>";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}

$conn->close();
?>
