# Ecommerce Platform Deployment with k3s

This project sets up a PHP-based ecommerce platform with a MySQL database using k3s and GitHub Actions CI/CD pipeline.

## Project Structure
```
- **.github/workflows/deploy.yml**: CI/CD pipeline for deployment.
- **k3s/**: Kubernetes manifests for deployments, secrets, and services.
- **web/**: Application source code and Dockerfile.
- **sql/**: Database initialization script.
```