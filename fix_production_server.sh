#!/bin/bash
# Script to reset production server to match origin/main

echo "Fetching latest from origin..."
git fetch origin

echo "Current HEAD:"
git log --oneline -1 HEAD

echo "Origin/main HEAD:"
git log --oneline -1 origin/main

echo "Resetting to origin/main..."
git reset --hard origin/main

echo "Cleaning untracked files..."
git clean -fd

echo "Verifying reset..."
git log --oneline -5

echo "Status check:"
git status

echo "Done! Server is now synced with origin/main"

