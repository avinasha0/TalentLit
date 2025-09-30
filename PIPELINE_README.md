# Kanban Pipeline Feature

## Overview
The Kanban pipeline feature allows recruiters to manage job applications by dragging and dropping them between different stages of the hiring process.

## Access
To access the pipeline for a specific job:
1. Navigate to the job details page: `/{tenant}/jobs/{job_id}`
2. Click the "View Pipeline" button
3. Or directly access: `/{tenant}/jobs/{job_id}/pipeline`

## Features
- **Drag & Drop**: Move applications between stages by dragging application cards
- **Real-time Updates**: Stage counts update automatically after moves
- **Audit Trail**: All moves are logged with user and timestamp information
- **Role-based Access**: Only Owner, Admin, and Recruiter roles can move applications
- **Responsive Design**: Works on desktop and mobile devices

## Permissions
- **View Pipeline**: Owner, Admin, Recruiter, Hiring Manager
- **Move Applications**: Owner, Admin, Recruiter only

## Technical Details
- Uses HTML5 Drag & Drop API
- AJAX requests for move operations
- Database transactions ensure data consistency
- Optimistic UI updates with error rollback
- Stage positioning system for ordering within stages

## Database Changes
- Added `stage_position` column to `applications` table
- Created `application_events` table for audit trail
- Added composite index for efficient querying

## API Endpoints
- `GET /{tenant}/jobs/{job}/pipeline` - Pipeline view
- `GET /{tenant}/jobs/{job}/pipeline.json` - JSON data for refresh
- `POST /{tenant}/jobs/{job}/pipeline/move` - Move application between stages
