import React, { useEffect, useState } from "react";
import axios from "axios";

export default function InstallerDatabase() {
	const [themes, setThemes] = useState([]);
	const [loading, setLoading] = useState(true);
	const [error, setError] = useState("");
	const [selectedTheme, setSelectedTheme] = useState("");

	useEffect(() => {
		axios.get("/api/installer/themes")
			.then((response) => {
				const themeList = response.data?.themes ?? [];
				setThemes(themeList);
				if (themeList.length > 0) {
					setSelectedTheme(String(themeList[0].id));
				}
			})
			.catch((err) => {
				const message = err.response?.data?.message || "Unable to load themes. Please complete step one first.";
				setError(message);
			})
			.finally(() => {
				setLoading(false);
			});
	}, []);

	const handleSubmit = async (e) => {
		e.preventDefault();

		if (!selectedTheme) {
			alert("Please select a theme.");
			return;
		}

		try {
			const response = await axios.post("/api/installer/step-two", {
				theme_id: selectedTheme,
			});

			if (response.data.status) {
				alert("Theme selected successfully.");
			}
		} catch (err) {
			alert(err.response?.data?.message || "Theme selection failed.");
		}
	};

	if (loading) {
		return (
			<div style={styles.wrapper}>
				<div style={styles.card}>Loading themes...</div>
			</div>
		);
	}

	if (error) {
		return (
			<div style={styles.wrapper}>
				<div style={styles.card}>
					<h2 style={styles.title}>Theme Selection</h2>
					<p style={styles.error}>{error}</p>
					<button style={styles.secondaryButton} onClick={() => {
						window.location.href = "/installer";
					}}>
						Back to Step 1
					</button>
				</div>
			</div>
		);
	}

	return (
		<div style={styles.wrapper}>
			<div style={styles.card}>
				<h2 style={styles.title}>Select Your Theme 🎨</h2>
				<form onSubmit={handleSubmit}>
					<div style={styles.themeGrid}>
						{themes.map((theme) => {
							const checked = selectedTheme === String(theme.id);

							return (
								<label key={theme.id} style={{ ...styles.themeCard, ...(checked ? styles.themeCardActive : {}) }}>
									<input
										type="radio"
										name="theme"
										value={theme.id}
										checked={checked}
										onChange={(e) => setSelectedTheme(e.target.value)}
										style={styles.radio}
									/>

									{theme.preview_image ? (
										<img
											src={`/storage/${theme.preview_image}`}
											alt={theme.name}
											style={styles.previewImage}
										/>
									) : (
										<div style={styles.placeholder}>No Preview</div>
									)}

									<div style={styles.themeName}>{theme.name}</div>
								</label>
							);
						})}
					</div>

					<button type="submit" style={styles.button}>
						Save Theme Selection
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
		padding: "32px",
		borderRadius: "14px",
		width: "860px",
		boxShadow: "0 10px 35px rgba(0,0,0,0.08)",
	},
	title: {
		textAlign: "center",
		marginBottom: "24px",
		fontSize: "26px",
		fontWeight: "700",
	},
	themeGrid: {
		display: "grid",
		gridTemplateColumns: "repeat(3, 1fr)",
		gap: "16px",
		marginBottom: "22px",
	},
	themeCard: {
		border: "1px solid #dcdfe6",
		borderRadius: "10px",
		padding: "10px",
		display: "flex",
		flexDirection: "column",
		gap: "10px",
		cursor: "pointer",
	},
	themeCardActive: {
		border: "2px solid #4f46e5",
	},
	radio: {
		alignSelf: "flex-start",
	},
	previewImage: {
		width: "100%",
		height: "120px",
		objectFit: "cover",
		borderRadius: "8px",
		border: "1px solid #eef1f7",
	},
	placeholder: {
		width: "100%",
		height: "120px",
		borderRadius: "8px",
		border: "1px dashed #d6dbe5",
		display: "flex",
		justifyContent: "center",
		alignItems: "center",
		color: "#7b8292",
		fontSize: "13px",
	},
	themeName: {
		fontWeight: "600",
		fontSize: "14px",
	},
	button: {
		width: "100%",
		padding: "14px",
		background: "#2563eb",
		color: "#fff",
		border: "none",
		borderRadius: "9px",
		cursor: "pointer",
		fontWeight: "600",
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
	error: {
		color: "#b91c1c",
		marginBottom: "14px",
	},
};
