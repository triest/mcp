# 3-specs — naming conventions

All spec elements in this folder use stable, numbered codes so they can be referenced from tasks, code comments and commit messages without ambiguity.

- Actor: `ACTOR-{number}-NAME-IN-MODULE` — e.g. `ACTOR-1-USER-IN-TASKTRACKER`
- Domain event: `EVT-{number}-NAME-IN-MODULE` — e.g. `EVT-1-CREATE-IN-TASKTRACKER`
- Entity: `ENT-{number}-NAME-IN-MODULE` — e.g. `ENT-1-TASK-IN-TASKTRACKER`
- Use case: `UC-{number}-ACTOR-{number}-EVT-{number}-ENT-{number}-RESULT-IN-MODULE` — e.g. `UC-1-ACTOR-1-EVT-1-ENT-1-CREATED-IN-TASKTRACKER`

Rules:

- Codes are stable forever once published. Extending the spec means adding new codes, never renaming or repurposing an existing one.
- `{number}` is a simple incrementing integer per kind (actors, events, entities, use cases each have their own counter), not a global counter.
- `MODULE` here is `TASKTRACKER` (the whole project is a single module — no per-domain split was needed at this scale).
- This folder's files are the same model as `../../tasks-naming-spec.md` (the RA-notation spec, `RA-TASKS-...` codes), expressed in this template's flat file layout. When the two disagree, `tasks-naming-spec.md` is authoritative — it was produced first and is the more detailed source; the codes in this folder's filenames are a lighter cross-reference layer on top of it, not a rename of it.
- One use case = one concrete outcome. A single API call that can succeed or fail in different domain-relevant ways gets one use-case file per outcome, not one file with branches.
