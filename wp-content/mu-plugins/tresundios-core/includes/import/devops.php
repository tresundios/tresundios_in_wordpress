<?php
/**
 * DevOps & Cloud gigs. New service line (not in the original sites), priced for both markets.
 * USD and INR are set separately, in line with the existing price ratio (about ₹88 per $1).
 *
 * @package Tresundios\Core
 */

defined( 'ABSPATH' ) || exit;

return array(
	array(
		'id'          => 'devops-cicd',
		'name'        => 'CI/CD pipeline setup',
		'description' => 'Automatic tests, builds and deployments on every push, so releases stop being a manual job.',
		'usd'         => 340,
		'inr'         => 30000,
		'billing'     => 'per-project',
		'features'    => array( 'GitHub Actions, GitLab CI or Bitbucket Pipelines', 'Build, test and deploy stages', 'Staging and production environments' ),
	),
	array(
		'id'          => 'devops-docker',
		'name'        => 'Docker containerisation',
		'description' => 'Package your web app, API or worker into containers that run the same everywhere.',
		'usd'         => 225,
		'inr'         => 20000,
		'billing'     => 'starting-from',
		'per'         => 'per-project',
		'features'    => array( 'Dockerfiles and docker-compose', 'Local development setup', 'Image size and security tuning' ),
	),
	array(
		'id'          => 'devops-server',
		'name'        => 'Cloud server setup and hardening',
		'description' => 'A secure, production-ready server on AWS, Google Cloud, DigitalOcean or Hetzner.',
		'usd'         => 285,
		'inr'         => 25000,
		'billing'     => 'one-time',
		'features'    => array( 'Firewall, SSH keys and automatic updates', 'SSL, domain and DNS setup', 'Web server and database configuration' ),
	),
	array(
		'id'          => 'devops-migration',
		'name'        => 'Website or app migration to the cloud',
		'description' => 'Move from shared hosting or an old server with minimal downtime.',
		'usd'         => 455,
		'inr'         => 40000,
		'billing'     => 'starting-from',
		'per'         => 'per-project',
		'features'    => array( 'Migration plan and dry run', 'Data and file transfer', 'DNS cut-over and rollback plan' ),
	),
	array(
		'id'          => 'devops-monitoring',
		'name'        => 'Monitoring, logging and uptime alerts',
		'description' => 'Know about problems before your customers do.',
		'usd'         => 170,
		'inr'         => 15000,
		'billing'     => 'one-time',
		'features'    => array( 'Uptime and SSL expiry checks', 'Error and performance dashboards', 'Email, Slack or WhatsApp alerts' ),
	),
	array(
		'id'          => 'devops-backup',
		'name'        => 'Backup and disaster recovery setup',
		'description' => 'Automatic, tested backups and a written plan to restore them.',
		'usd'         => 205,
		'inr'         => 18000,
		'billing'     => 'one-time',
		'features'    => array( 'Daily off-site backups', 'Restore test', 'Recovery runbook' ),
	),
	array(
		'id'          => 'devops-iac',
		'name'        => 'Infrastructure as code (Terraform)',
		'description' => 'Your cloud setup written as code: repeatable, reviewable and easy to rebuild.',
		'usd'         => 570,
		'inr'         => 50000,
		'billing'     => 'starting-from',
		'per'         => 'per-project',
		'features'    => array( 'Terraform modules for your stack', 'Remote state and environments', 'Documentation and handover' ),
	),
	array(
		'id'          => 'devops-kubernetes',
		'name'        => 'Kubernetes cluster setup',
		'description' => 'A managed Kubernetes cluster for teams running several services.',
		'usd'         => 1135,
		'inr'         => 100000,
		'billing'     => 'starting-from',
		'per'         => 'per-project',
		'features'    => array( 'EKS, GKE or DigitalOcean Kubernetes', 'Ingress, SSL and autoscaling', 'Helm charts and deployment pipeline' ),
	),
	array(
		'id'          => 'devops-cost-audit',
		'name'        => 'Cloud cost optimisation audit',
		'description' => 'Find and cut wasted cloud spend, with a prioritised list of fixes.',
		'usd'         => 285,
		'inr'         => 25000,
		'billing'     => 'per-project',
		'features'    => array( 'Usage and billing review', 'Right-sizing recommendations', 'Savings report' ),
	),
	array(
		'id'          => 'devops-retainer',
		'name'        => 'Managed DevOps retainer',
		'description' => 'Ongoing care for your servers and pipelines, with a monthly report.',
		'usd'         => 340,
		'inr'         => 30000,
		'billing'     => 'monthly',
		'features'    => array( 'Patching and security updates', 'Monitoring and incident response', 'Up to 8 hours of changes per month' ),
	),
);
