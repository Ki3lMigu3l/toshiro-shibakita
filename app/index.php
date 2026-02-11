<?php
ini_set("display_errors", 1);
error_reporting(E_ALL);

header('Content-Type: text/html; charset=utf-8');

$phpVersion = phpversion();
$hostName   = gethostname();

$servername = getenv("DB_HOST") ?: "changeme";
$username   = getenv("DB_USER") ?: "changeme";
$password   = getenv("DB_PASS") ?: "changeme";
$database   = getenv("DB_NAME") ?: "changeme";

$statusMsg  = null;
$statusType = "info"; // success | danger | warning | info

$link = @new mysqli($servername, $username, $password, $database);

if ($link->connect_error) {
    $statusType = "danger";
    $statusMsg  = "Database connection failed: " . $link->connect_error;
    $rows = [];
} else {
    // Insert
    $valor_rand1 = rand(1, 999);
    $valor_rand2 = strtoupper(substr(bin2hex(random_bytes(4)), 0, 8));

    $query = "INSERT INTO dados (AlunoID, Nome, Sobrenome, Endereco, Cidade, Host)
              VALUES (?, ?, ?, ?, ?, ?)";

    $stmt = $link->prepare($query);

    if (!$stmt) {
        $statusType = "danger";
        $statusMsg  = "Prepare failed: " . $link->error;
    } else {
        $stmt->bind_param("isssss", $valor_rand1, $valor_rand2, $valor_rand2, $valor_rand2, $valor_rand2, $hostName);

        if ($stmt->execute()) {
            $statusType = "success";
            $statusMsg  = "New record created successfully.";
        } else {
            $statusType = "danger";
            $statusMsg  = "Insert error: " . $stmt->error;
        }
        $stmt->close();
    }

    // Fetch last 10
    $rows = [];
    $result = $link->query("SELECT * FROM dados ORDER BY AlunoID DESC LIMIT 10");
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }
    } else {
        $statusType = "warning";
        $statusMsg  = ($statusMsg ? $statusMsg . " " : "") . "Query error: " . $link->error;
    }

    $link->close();
}
?>
<!doctype html>
<html lang="pt-BR">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Docker Microservices Demo</title>

  <!-- Bootstrap 5 (CDN) -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container">
    <span class="navbar-brand">Docker • Microservices Demo</span>
    <div class="d-flex gap-2">
      <span class="badge text-bg-secondary">PHP <?php echo htmlspecialchars($phpVersion); ?></span>
      <span class="badge text-bg-info">Container <?php echo htmlspecialchars($hostName); ?></span>
    </div>
  </div>
</nav>

<main class="container py-4">

  <div class="row g-3">
    <div class="col-12 col-lg-4">
      <div class="card shadow-sm">
        <div class="card-body">
          <h5 class="card-title mb-3">Runtime</h5>

          <div class="mb-2">
            <div class="text-muted small">PHP Version</div>
            <div class="fw-semibold"><?php echo htmlspecialchars($phpVersion); ?></div>
          </div>

          <div class="mb-2">
            <div class="text-muted small">Container (hostname)</div>
            <div class="fw-semibold"><?php echo htmlspecialchars($hostName); ?></div>
            <div class="text-muted small">Use isso para validar o load balancer (F5 várias vezes).</div>
          </div>

          <hr>

          <div class="mb-2">
            <div class="text-muted small">Database Host</div>
            <div class="fw-semibold"><?php echo htmlspecialchars($servername); ?></div>
          </div>

          <div class="mb-2">
            <div class="text-muted small">Database Name</div>
            <div class="fw-semibold"><?php echo htmlspecialchars($database); ?></div>
          </div>

          <div class="alert alert-<?php echo $statusType; ?> mb-0 mt-3" role="alert">
            <?php echo htmlspecialchars($statusMsg ?? "Ready."); ?>
          </div>
        </div>
      </div>
    </div>

    <div class="col-12 col-lg-8">
      <div class="card shadow-sm">
        <div class="card-body">
          <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="card-title mb-0">Last 10 Inserts</h5>
            <span class="badge text-bg-dark">Table: dados</span>
          </div>

          <div class="table-responsive">
            <table class="table table-sm table-striped table-hover align-middle mb-0">
              <thead class="table-dark">
                <tr>
                  <th>AlunoID</th>
                  <th>Nome</th>
                  <th>Sobrenome</th>
                  <th>Endereco</th>
                  <th>Cidade</th>
                  <th>Host</th>
                </tr>
              </thead>
              <tbody>
                <?php if (empty($rows)): ?>
                  <tr>
                    <td colspan="6" class="text-muted">No rows to display.</td>
                  </tr>
                <?php else: ?>
                  <?php foreach ($rows as $r): ?>
                    <tr>
                      <td><?php echo htmlspecialchars($r["AlunoID"]); ?></td>
                      <td><?php echo htmlspecialchars($r["Nome"]); ?></td>
                      <td><?php echo htmlspecialchars($r["Sobrenome"]); ?></td>
                      <td><?php echo htmlspecialchars($r["Endereco"]); ?></td>
                      <td><?php echo htmlspecialchars($r["Cidade"]); ?></td>
                      <td><span class="badge text-bg-info"><?php echo htmlspecialchars($r["Host"]); ?></span></td>
                    </tr>
                  <?php endforeach; ?>
                <?php endif; ?>
              </tbody>
            </table>
          </div>

          <div class="text-muted small mt-3">
            Dica: rode <code>docker compose up -d --scale app=3</code> e atualize a página para observar hosts diferentes.
          </div>
        </div>
      </div>
    </div>
  </div>

</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
