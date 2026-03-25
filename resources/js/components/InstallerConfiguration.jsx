import React, { useEffect, useState } from "react";
import axios from "axios";

export default function InstallerConfiguration() {
	const [loading, setLoading] = useState(true);
	const [error, setError] = useState("");
	const [successMessage, setSuccessMessage] = useState("");
	const [isSaving, setIsSaving] = useState(false);
	const [progress, setProgress] = useState(0);
	const [formData, setFormData] = useState({
		app_name: "",
		website_url: "",
		db_database: "",
		db_username: "",
		db_password: "",
	});

	useEffect(() => {
		axios.get("/api/installer/step-three")
			.then((response) => {
				const data = response.data?.data ?? {};
				setFormData({
					app_name: data.app_name ?? "",
					website_url: data.website_url ?? "",
					db_database: data.db_database ?? "",
					db_username: data.db_username ?? "",
					db_password: data.db_password ?? "",
				});
			})
			.catch((err) => {
				const redirectTo = err.response?.data?.redirect_to;
				const message = err.response?.data?.message || "Unable to load installer configuration.";

				if (redirectTo) {
					window.location.href = redirectTo;
					return;
				}

				setError(message);
			})
			.finally(() => {
				setLoading(false);
			});
	}, []);

	const handleChange = (event) => {
		const { name, value } = event.target;
		setError("");

		setFormData((current) => ({
			...current,
			[name]: value,
		}));
	};

	const handleSubmit = async (event) => {
		event.preventDefault();
		setError("");
		setSuccessMessage("");
		setIsSaving(true);
		setProgress(0);

		const progressTimer = window.setInterval(() => {
			setProgress((current) => {
				if (current >= 90) {
					return current;
				}

				if (current < 60) {
					return current + 12;
				}

				return current + 4;
			});
		}, 180);

		try {
			const response = await axios.post("/api/installer/step-three", formData);

			if (response.data.status) {
				window.clearInterval(progressTimer);
				setProgress(100);
				setSuccessMessage(response.data.message);
				await new Promise((resolve) => window.setTimeout(resolve, 800));
				window.location.href = response.data?.data?.redirect_to || "/admin/login";
			}
		} catch (err) {
			window.clearInterval(progressTimer);
			setProgress(0);
			const redirectTo = err.response?.data?.redirect_to;
			setError(err.response?.data?.message || "Unable to save database configuration.");

			if (redirectTo) {
				await new Promise((resolve) => window.setTimeout(resolve, 500));
				window.location.href = redirectTo;
			}
		} finally {
			window.clearInterval(progressTimer);
			setIsSaving(false);
		}
	};

	if (loading) {
		return (
			<div style={styles.wrapper}>
				<div style={styles.card}>Loading configuration...</div>
			</div>
		);
	}

	if (error && !formData.app_name && !formData.website_url) {
		return (
			<div style={styles.wrapper}>
				<div style={styles.card}>
					<h2 style={styles.title}>Database Setup</h2>
					<p style={styles.error}>{error}</p>
					<button type="button" style={styles.secondaryButton} onClick={() => {
						window.location.href = "/installer/theme";
					}}>
						Back to Theme Selection
					</button>
				</div>
			</div>
		);
	}

	return (
		<div style={styles.wrapper}>
			<div style={styles.card}>
				<div style={styles.headerRow}>
					<div>
						<p style={styles.eyebrow}>Step 3 of 3</p>
						<h2 style={styles.title}>Application and Database Settings</h2>
						<p style={styles.subtitle}>App name and website URL are prefilled from step one. Review them and add your database credentials.</p>
					</div>
					<button type="button" style={styles.linkButton} onClick={() => {
						window.location.href = "/installer/theme";
					}} disabled={isSaving}>
						Back
					</button>
				</div>

				<form onSubmit={handleSubmit}>
					{isSaving ? (
						<div style={styles.loaderBox}>
							<div style={styles.loaderHeader}>
								<span style={styles.loaderTitle}>Configuring database and updating environment</span>
								<span style={styles.loaderPercent}>{progress}%</span>
							</div>
							<div style={styles.progressTrack}>
								<div style={{ ...styles.progressBar, width: `${progress}%` }} />
							</div>
							<p style={styles.loaderText}>Saving APP_NAME, APP_URL, DB_DATABASE, DB_USERNAME, and DB_PASSWORD into the environment file.</p>
						</div>
					) : null}

					<div style={styles.grid}>
						<div style={styles.field}>
							<label style={styles.label}>App Name</label>
							<input
								name="app_name"
								value={formData.app_name}
								onChange={handleChange}
								placeholder="News Portal"
								disabled={isSaving}
								style={{ ...styles.input, ...(isSaving ? styles.inputDisabled : {}) }}
							/>
						</div>

						<div style={styles.field}>
							<label style={styles.label}>Website URL</label>
							<input
								name="website_url"
								value={formData.website_url}
								onChange={handleChange}
								placeholder="https://example.com"
								disabled={isSaving}
								style={{ ...styles.input, ...(isSaving ? styles.inputDisabled : {}) }}
							/>
						</div>

						<div style={styles.field}>
							<label style={styles.label}>Database Name</label>
							<input
								name="db_database"
								value={formData.db_database}
								onChange={handleChange}
								placeholder="newsportal"
								disabled={isSaving}
								style={{ ...styles.input, ...(isSaving ? styles.inputDisabled : {}) }}
							/>
						</div>

						<div style={styles.field}>
							<label style={styles.label}>Database Username</label>
							<input
								name="db_username"
								value={formData.db_username}
								onChange={handleChange}
								placeholder="root"
								disabled={isSaving}
								style={{ ...styles.input, ...(isSaving ? styles.inputDisabled : {}) }}
							/>
						</div>

						<div style={{ ...styles.field, ...styles.fullWidth }}>
							<label style={styles.label}>Database Password</label>
							<input
								type="password"
								name="db_password"
								value={formData.db_password}
								onChange={handleChange}
								placeholder="Enter database password"
								disabled={isSaving}
								style={{ ...styles.input, ...(isSaving ? styles.inputDisabled : {}) }}
							/>
						</div>
					</div>

					{error ? <p style={styles.error}>{error}</p> : null}
					{successMessage ? <p style={styles.success}>{successMessage}</p> : null}

					<button type="submit" disabled={isSaving} style={{ ...styles.primaryButton, ...(isSaving ? styles.primaryButtonDisabled : {}) }}>
						{isSaving ? "Configuring..." : "Save Database Settings"}
					</button>
				</form>
			</div>
		</div>
	);
}

const styles = {
	wrapper: {
		minHeight: "100vh",
		display: "flex",
		justifyContent: "center",
		alignItems: "center",
		background: "#f5f7fb",
		padding: "20px",
	},
	card: {
		background: "#fff",
		padding: "36px",
		borderRadius: "14px",
		width: "760px",
		boxShadow: "0 10px 35px rgba(0,0,0,0.08)",
	},
	headerRow: {
		display: "flex",
		justifyContent: "space-between",
		alignItems: "flex-start",
		gap: "20px",
		marginBottom: "24px",
	},
	eyebrow: {
		margin: 0,
		fontSize: "12px",
		fontWeight: "700",
		letterSpacing: "0.08em",
		textTransform: "uppercase",
		color: "#2563eb",
	},
	title: {
		margin: "8px 0 10px",
		fontSize: "28px",
		fontWeight: "700",
	},
	subtitle: {
		margin: 0,
		fontSize: "14px",
		color: "#64748b",
		maxWidth: "520px",
		lineHeight: 1.6,
	},
	grid: {
		display: "grid",
		gridTemplateColumns: "1fr 1fr",
		gap: "20px",
	},
	field: {
		display: "flex",
		flexDirection: "column",
		gap: "8px",
	},
	fullWidth: {
		gridColumn: "1 / -1",
	},
	label: {
		fontWeight: "600",
		fontSize: "14px",
	},
	input: {
		padding: "14px",
		borderRadius: "8px",
		border: "1px solid #dcdfe6",
		fontSize: "15px",
	},
	inputDisabled: {
		background: "#f8fafc",
		cursor: "not-allowed",
	},
	loaderBox: {
		marginBottom: "24px",
		padding: "18px",
		borderRadius: "12px",
		background: "#eff6ff",
		border: "1px solid #bfdbfe",
	},
	loaderHeader: {
		display: "flex",
		justifyContent: "space-between",
		alignItems: "center",
		gap: "12px",
		marginBottom: "12px",
	},
	loaderTitle: {
		fontWeight: "700",
		color: "#1d4ed8",
	},
	loaderPercent: {
		fontWeight: "700",
		color: "#0f172a",
	},
	progressTrack: {
		width: "100%",
		height: "12px",
		borderRadius: "999px",
		background: "#dbeafe",
		overflow: "hidden",
	},
	progressBar: {
		height: "100%",
		borderRadius: "999px",
		background: "linear-gradient(90deg, #2563eb 0%, #0f766e 100%)",
		transition: "width 0.18s ease",
	},
	loaderText: {
		margin: "12px 0 0",
		fontSize: "13px",
		color: "#475569",
		lineHeight: 1.5,
	},
	primaryButton: {
		marginTop: "24px",
		width: "100%",
		padding: "14px",
		background: "#2563eb",
		color: "#fff",
		border: "none",
		borderRadius: "9px",
		cursor: "pointer",
		fontWeight: "600",
	},
	primaryButtonDisabled: {
		opacity: 0.7,
		cursor: "not-allowed",
	},
	secondaryButton: {
		width: "100%",
		padding: "12px",
		background: "#eef2ff",
		color: "#3730a3",
		border: "none",
		borderRadius: "8px",
		cursor: "pointer",
		fontWeight: "600",
	},
	linkButton: {
		padding: "10px 14px",
		background: "#f8fafc",
		color: "#0f172a",
		border: "1px solid #e2e8f0",
		borderRadius: "8px",
		cursor: "pointer",
		fontWeight: "600",
	},
	error: {
		marginTop: "18px",
		marginBottom: 0,
		color: "#b91c1c",
	},
	success: {
		marginTop: "18px",
		marginBottom: 0,
		color: "#15803d",
	},
};