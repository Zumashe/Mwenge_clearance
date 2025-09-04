<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Mwenge Clearance System</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <style>
    body {
      background: linear-gradient(135deg, #0d1a4a 0%, #f9d923 100%);
      min-height: 100vh;
    }
    .hero {
      background: rgba(13, 26, 74, 0.88) url('mwecau.png') center/cover no-repeat;
      background-blend-mode: darken;
      color: #fff;
      padding: 90px 0 70px 0;
      text-align: center;
      border-radius: 0 0 2rem 2rem;
      box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
    }
    .hero-title {
      font-size: 2.8rem;
      font-weight: bold;
      letter-spacing: 1px;
    }
    .hero-desc {
      font-size: 1.3rem;
      margin-bottom: 2rem;
    }
    .cta-btn {
      font-size: 1.2rem;
      padding: 0.75rem 2.5rem;
      border-radius: 2rem;
      background: #f9d923;
      color: #0d1a4a;
      font-weight: bold;
      text-decoration: none;
      transition: 0.3s;
    }
    .cta-btn:hover {
      background: #ffe066;
    }
    .mwenge-img {
      width: 130px;
      height: 130px;
      object-fit: cover;
      border-radius: 50%;
      border: 4px solid #f9d923;
      margin-bottom: 1.5rem;
      background: #fff;
    }
    footer {
      background: #0d1a4a;
      color: #fff;
      padding: 1.5rem 0;
      text-align: center;
      margin-top: 3rem;
      border-radius: 2rem 2rem 0 0;
    }
  </style>
</head>
<body>
  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg navbar-dark" style="background: #0d1a4a;">
    <div class="container">
      <a class="navbar-brand fw-bold" href="#">mwecau Clearance System</a>
      <div class="ms-auto">
        <a href="login.php" class="btn btn-outline-light me-2">Login</a>
        <a href="register.php" class="btn btn-warning text-dark fw-bold">Register</a>
      </div>
    </div>
  </nav>

  <!-- Hero -->
  <section class="hero">
    <div class="container">
      <img src="mwecau.png" alt="Mwenge" class="mwenge-img shadow" />
      <div class="hero-title mb-3">Welcome to mwecau Clearance System</div>
      <div class="hero-desc mb-4">
        Simplify your university clearance process.<br />
        Track requests, manage approvals, and download clearance certificates easily.<br />
        <span style="color: #f9d923; font-weight: bold;">Start Now!</span>
      </div>
      <a href="register.php" class="cta-btn shadow">Get Started</a>
    </div>
  </section>

  <!-- Info -->
  <section class="container my-5">
    <div class="row align-items-center">
      <div class="col-md-6 mb-4">
        <h2 class="fw-bold" style="color: #0d1a4a;">Why Use This System?</h2>
        <ul class="list-unstyled mt-3">
          <li class="mb-2"><span class="badge bg-warning text-dark me-2">1</span> Students can submit and track clearance requests.</li>
          <li class="mb-2"><span class="badge bg-warning text-dark me-2">2</span> Admin can view progress in all offices and delete requests.</li>
          <li class="mb-2"><span class="badge bg-warning text-dark me-2">3</span> Admin can see approved students and download PDF list.</li>
          <li class="mb-2"><span class="badge bg-warning text-dark me-2">4</span> Admin can change usernames & passwords for all roles.</li>
        </ul>
      </div>
      <div class="col-md-6 text-center">
        <img src="mwecau.png" alt="Mwenge" class="img-fluid rounded shadow" style="max-width: 300px" />
      </div>
    </div>
  </section>

  <!-- Footer -->
  <footer>
    <div class="container">
      <div>&copy; <?=date("Y")?> Mwenge Clearance System. All rights reserved.</div>
    </div>
  </footer>
</body>
</html>
