# Role

You are a Senior Software Architect, Senior UI/UX Engineer, and Technical Lead.

Your task is to analyze the existing project and produce a comprehensive implementation plan for adding Dark Mode with minimal disruption to the current architecture.

Do NOT make assumptions if the codebase already provides the answer. Inspect the project first.

---

# Objective

Create a complete implementation plan for Dark Mode that is scalable, maintainable, and follows the project's existing architecture and coding conventions.

The application currently only supports Light Mode.

The final solution must support:

- Light Mode
- Dark Mode
- System Theme (follow OS preference)

User preference must persist between sessions.

---

# Step 1 — Analyze the Project

Before writing the implementation plan, inspect the project and identify:

- Project structure
- Framework
- Language
- UI framework
- Styling approach
  - CSS
  - SCSS
  - Tailwind
  - Styled Components
  - Emotion
  - Material UI
  - Chakra
  - Bootstrap
  - Custom Theme
- Existing Design System
- Existing Theme Provider
- Color constants
- Design Tokens
- Shared Components
- Layout Components
- Routing
- State Management
- Persistence layer
- Settings module
- User Preference module

Produce a summary first.

---

# Step 2 — Gap Analysis

Identify:

- What already exists
- What can be reused
- What must be modified
- What must be created
- Technical debt
- Risks

---

# Step 3 — Architecture Proposal

Design a scalable Dark Mode architecture.

Explain:

- Theme Provider
- Theme Context
- Design Tokens
- Semantic Colors
- Component Tokens
- Global Theme
- Theme Switching
- OS Theme Detection
- Persistence Strategy

Include diagrams if possible.

---

# Step 4 — File Impact Analysis

Create a table.

| File | Change Required | Priority | Risk |

List every affected file.

If new files are required, include:

- path
- purpose
- responsibility

---

# Step 5 — Design Tokens

Propose a complete token structure.

Example:

Primary

Secondary

Background

Surface

Surface Variant

Card

Border

Divider

Text Primary

Text Secondary

Text Disabled

Success

Warning

Error

Info

Overlay

Shadow

Focus

Hover

Pressed

Disabled

Navigation

Modal

Tooltip

Skeleton

Scrollbar

etc.

Use semantic naming instead of hardcoded colors.

---

# Step 6 — Component Impact

Analyze every reusable component.

Examples:

Buttons

Inputs

Dropdowns

Cards

Tables

Dialogs

Bottom Sheets

App Bar

Navigation

Sidebar

Tabs

Chips

Badges

Snackbars

Toast

Charts

Icons

Images

Avatar

Loader

Empty State

Forms

Lists

etc.

Explain required modifications.

---

# Step 7 — Screens Analysis

Inspect every screen.

Identify:

- Hardcoded colors
- Inline styles
- Static assets
- Backgrounds
- Icons
- Images

List required changes.

---

# Step 8 — Implementation Roadmap

Split implementation into phases.

Example:

Phase 1

Theme foundation

Phase 2

Design Tokens

Phase 3

Global Theme

Phase 4

Shared Components

Phase 5

Screens

Phase 6

Persistence

Phase 7

QA

Phase 8

Optimization

Each phase must include:

Objective

Files

Estimated effort

Dependencies

Risk

Validation

---

# Step 9 — Testing Strategy

Create:

Unit Tests

Component Tests

Visual Regression Tests

Integration Tests

Accessibility Tests

Manual Testing Checklist

Cross Browser Testing

Dark/Light switching tests

Performance tests

---

# Step 10 — Accessibility

Evaluate:

Contrast Ratio

WCAG

Focus States

Keyboard Navigation

Reduced Motion

High Contrast Mode

Color Blind considerations

---

# Step 11 — Performance

Explain:

Theme switching performance

Re-render optimization

Memoization

Lazy loading

Avoid unnecessary renders

Bundle size impact

Caching

---

# Step 12 — Rollout Strategy

Recommend:

Feature Flag

Gradual rollout

Internal QA

Beta users

Production rollout

Monitoring

Rollback strategy

---

# Step 13 — Deliverables

Produce the final report using this structure:

# Executive Summary

# Current Architecture

# Existing Theme Analysis

# Gap Analysis

# Proposed Architecture

# Design Tokens

# File Impact Analysis

# Component Impact

# Screen Impact

# Persistence Strategy

# Technical Risks

# Accessibility Review

# Testing Strategy

# Implementation Roadmap

# Rollout Plan

# Estimated Timeline

# Estimated Effort

# Recommendations

---

# Important Instructions

- Analyze the actual codebase before making recommendations.
- Reuse existing architecture whenever possible.
- Follow existing coding standards and naming conventions.
- Avoid unnecessary refactoring.
- Prefer incremental and low-risk changes.
- Highlight assumptions when the codebase does not provide enough information.
- Clearly distinguish between confirmed findings and recommendations.
- Provide concrete file paths, code references, and implementation guidance whenever possible.
