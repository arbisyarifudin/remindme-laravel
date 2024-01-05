<template>
  <div class="page-wrapper">
    <div class="page-content">
      <div class="container-fluid">
        <div class="row justify-content-center">
          <div class="col-md-6 col-sm-12">
            <div class="card">
              <div class="card-body">
                <div class="text-center">
                  <h1 class="display-1 font-weight-bold">404</h1>
                  <p class="h1">Page not found!</p>
                  <p class="h2 font-weight-normal mt-3 mb-4">The page you requested was not found.</p>
                  <router-link :to="{ name: 'App Home Page' }" class="btn btn-primary btn-lg">Back to Home</router-link>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<style lang="scss" scoped>
.page-wrapper {
  // background: #f1f5f7;
  min-height: 100vh;
}

.page-content {
  padding: 120px 0 0;
}

.card {
  border-radius: 5px;
  overflow: hidden;
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

.card-body {
  padding: 50px 30px;
}

.display-1 {
  font-size: 80px;
  font-weight: 700;
  color: #003049;
}

.h1 {
  font-size: 40px;
  font-weight: 600;
  color: #003049;
}

.h2 {
  font-size: 20px;
  font-weight: 600;
  color: #003049;
}

.btn {
  font-size: 16px;
  font-weight: 600;
  padding: 12px 30px;
  border-radius: 5px;
}

.btn-primary {
  background: #003049;
  border-color: #003049;
}

.btn-primary:hover {
  background: #23272b;
  border-color: #23272b;
}

@media (max-width: 767px) {
  .page-content {
    padding: 80px 0 0;
  }

  .display-1 {
    font-size: 60px;
  }

  .h1 {
    font-size: 30px;
  }

  .h2 {
    font-size: 18px;
  }
}
</style>
