# Mosne Maintenance Mode
Maintenance mode for development use. 

Add a checkbox in Settings->Reading to turn on/off the maintenance mode. 

If checked the class "m-maintenance-mode" is added to body, and code can be enclosed in an if statement and will be run only if current user is logged in and is an administrator
## Useful function
	m_is_maintenance_mode()
Evaluate the status and if checked the class "m-maintenance-mode" is added to body.
